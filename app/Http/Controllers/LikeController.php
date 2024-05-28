<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Publication;

class LikeController extends Controller
{
    public function addLike(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'publication_id' => 'required|exists:publications,id',
        ]);

        $like = Like::create([
            'user_id' => $request->user_id,
            'publication_id' => $request->publication_id,
        ]);

        if ($like) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function dropLike(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'publication_id' => 'required|exists:publications,id',
        ]);

        $like = Like::where('user_id', $request->user_id)
                    ->where('publication_id', $request->publication_id)
                    ->first();

        if ($like) {
            $like->delete();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Like not found']);
        }
    }

    public function updateNumberOfLikes(Request $request)
    {
        $request->validate([
            'id_publication' => 'required|exists:publications,id',
            'numberlike' => 'required|integer',
        ]);

        $publication = Publication::find($request->id_publication);
        $publication->numberlike = $request->numberlike;

        if ($publication->save()) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }
}
