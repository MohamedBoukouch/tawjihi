<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class PublicationController extends Controller
{
    // Add a publication and create a notification
    public function Add(Request $request)
    {
        $request->validate([
            'localisation' => 'required|string',
            'type' => 'required|string',
            'titel' => 'required|string',
            'description' => 'required|string',
            'link' => 'nullable|string',
            'link_titel' => 'nullable|string',
            'file.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for images
        ]);

        $publication = new Publication();
        $publication->localisation = $request->localisation;
        $publication->type = $request->type;
        $publication->date = now(); // Assuming you want to set the current date/time
        $publication->titel = $request->titel;
        $publication->description = $request->description;
        $publication->link = $request->link;
        $publication->link_titel = $request->link_titel;

        // Handle file upload and move files to the desired directory
        if ($request->hasFile('file')) {
            $files = [];
            foreach ($request->file('file') as $file) {
                $filename = rand() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('/images/publication'), $filename); // Move file to custom directory
                $files[] = $filename;
            }
            $publication->file = $files;
        }

        $publication->save();

        // // Create a notification
        $notification = new Notification();
        $notification->title = "Tawjihi";
        $notification->body = $publication->titel;
        $notification->active = 1;
        $notification->image = $files[0] ?? null; // Assuming you want to use the first image
        $notification->publication_id = $publication->id;
        $notification->save();

        
        // Send notification to Firebase Cloud Messaging
        $topic = 'your_topic_name'; // Replace with your topic name or token
        $pageid = $publication->id;
        $pagename = 'Publication Added';

        $result = $this->sendGCM("New Publication Added", $publication->titel, $topic, $pageid, $pagename);

        // Handle the response if needed
        // For example, log the result
        \Log::info('FCM Result:', ['result' => $result]);


        return response()->json(['status' => 'success', 'data' => $publication]);
    }

    // GETPUBLICATION
    public function getPublications(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'type' => 'nullable|string',
        ]);

        $id_user = $request->id_user;
        $type = $request->type;

        $publications = Publication::with(['favorites' => function ($query) use ($id_user) {
            $query->where('user_id', $id_user);
        }, 'likes' => function ($query) use ($id_user) {
            $query->where('user_id', $id_user);
        }])
        ->when($type, function ($query, $type) {
            return $query->where('type', $type);
        })
        ->orWhere('id', $type)
        ->orderBy('id', 'desc')
        ->get()
        ->map(function ($publication) {
            $publication->favorite = $publication->favorites->isNotEmpty();
            $publication->liked = $publication->likes->isNotEmpty();
            unset($publication->favorites, $publication->likes);
            return $publication;
        });

        if ($publications->isNotEmpty()) {
            return response()->json(['status' => 'success', 'data' => $publications]);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    // SEARCH
    public function search(Request $request)
    {
        $id_user = $request->input('id_user');
        $search_txt = $request->input('search_txt');
    
        $publications = DB::table('publications')
            ->select('publications.*', DB::raw('CASE WHEN favorites.user_id IS NOT NULL THEN 1 ELSE 0 END AS favorite'), DB::raw('CASE WHEN likes.user_id IS NOT NULL THEN 1 ELSE 0 END AS liked'))
            ->leftJoin('favorites', function ($join) use ($id_user) {
                $join->on('favorites.publication_id', '=', 'publications.id')
                    ->where('favorites.user_id', '=', $id_user);
            })
            ->leftJoin('likes', function ($join) use ($id_user) {
                $join->on('likes.publication_id', '=', 'publications.id')
                    ->where('likes.user_id', '=', $id_user);
            })
            ->where(function ($query) use ($search_txt) {
                $query->where('publications.localisation', 'like', "%$search_txt%")
                      ->orWhere('publications.type', 'like', "%$search_txt%")
                      ->orWhere('publications.titel', 'like', "%$search_txt%");
            })
            ->orderByDesc('publications.id')
            ->get();
    
        if ($publications->isNotEmpty()) {
            return response()->json(['status' => 'success', 'data' => $publications]);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function sendGCM($title, $message, $topic, $pageid, $pagename)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = [
            'Authorization' => 'key=' . 'your_server_key',
            'Content-Type' => 'application/json',
        ];

        $notification = [
            'title' => $title,
            'body' => $message,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'sound' => 'default',
        ];

        $data = [
            'pageid' => $pageid,
            'pagename' => $pagename,
        ];

        $fields = [
            'to' => '/topics/' . $topic,
            'priority' => 'high',
            'content_available' => true,
            'notification' => $notification,
            'data' => $data,
        ];

        $response = Http::withHeaders($headers)->post($url, $fields);

        // Log the response if needed
        \Log::info('FCM Response:', ['response' => $response->json()]);

        return $response->json();
    }
}
