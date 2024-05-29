<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Publication;

class FavoriteController extends Controller
{
    public function addFavorite(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'publication_id' => 'required|exists:publications,id',
        ]);

        $favorite = Favorite::create([
            'user_id' => $request->user_id,
            'publication_id' => $request->publication_id,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function deleteFavorite(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'publication_id' => 'required|exists:publications,id',
        ]);

        $favorite = Favorite::where('user_id', $request->user_id)
                            ->where('publication_id', $request->publication_id)
                            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Favorite not found']);
        }
    }

    public function selectFavorite(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
    
        // Retrieve favorites with publications and additional information
        $favorites = Favorite::where('user_id', $request->user_id)
                             ->with('publication')
                             ->get()
                             ->map(function ($favorite) {
                                 $publication = $favorite->publication;
                                 $publication->favorite = true; // Mark as favorite
                                 $publication->liked = $publication->likes()->where('user_id', $favorite->user_id)->exists(); // Check if liked by current user
                                 return $publication;
                             });
    
        // Check if any favorites were found
        if ($favorites->isNotEmpty()) {
            return response()->json(['status' => 'success', 'data' => $favorites]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No favorite publications found.']);
        }
    }
}
