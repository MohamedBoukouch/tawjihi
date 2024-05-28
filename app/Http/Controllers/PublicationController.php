<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Favorite;
use App\Models\Like;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    public function Add(Request $request)
    {
        $request->validate([
            'localisation' => 'required|string',
            'type' => 'required|string',
            'titel' => 'required|string',
            'description' => 'required|string',
            'link' => 'nullable|string',
            'link_titel' => 'nullable|string',
            'file.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for images
        ]);

        $publication = Publication::create([
            'localisation' => $request->localisation,
            'type' => $request->type,
            'date'=>now(),
            'titel' => $request->titel,
            'description' => $request->description,
            'link' => $request->link,
            'link_titel' => $request->link_titel,
            'file' => [], // Initialize as an empty array
        ]);

        
        // Handle file upload and store file names in the 'file' attribute
        if ($request->hasFile('file')) {
            $files = [];
            foreach ($request->file('file') as $file) {
                $filename = $file->store();
                $files[] = $filename;
            }
            $publication->file = $files;
            $publication->save();
        }

        return response()->json(['status' => 'success', 'data' => $publication]);
    }

//GETPUBLICATION
public function getPublications(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'type' => 'nullable|string',
        ]);

        $id_user = $request->id_user;
        $type = $request->type;

        $publications = Publication::with(['favorites' => function($query) use ($id_user) {
            $query->where('user_id', $id_user);
        }, 'likes' => function($query) use ($id_user) {
            $query->where('user_id', $id_user);
        }])
        ->when($type, function($query, $type) {
            return $query->where('type', $type);
        })
        ->orWhere('id', $type)
        ->orderBy('id', 'desc')
        ->get()
        ->map(function($publication) {
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

//SEARCH
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
            ->where('publications.localisation', 'like', "%$search_txt%")
            ->orWhere('publications.type', 'like', "%$search_txt%")
            ->orWhere('publications.titel', 'like', "%$search_txt%")
            ->orderByDesc('publications.id')
            ->get();

        if ($publications->isNotEmpty()) {
            return response()->json(['status' => 'success', 'data' => $publications]);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

}
