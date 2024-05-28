<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;


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
}
