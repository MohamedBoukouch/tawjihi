<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Publication; // Assuming you have a Publication model
use App\Models\User; // Assuming you have a User model

class CommentController extends Controller
{
    // Method to add a comment
    public function addComment(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer|exists:users,id',
            'id_publication' => 'required|integer|exists:publications,id',
            'text' => 'required|string',
        ]);

        $comment = Comment::create($request->all());

        if ($comment) {
            // Increment the number of comments for the publication
            Publication::where('id', $request->id_publication)->increment('numbercomment');

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to add the comment'], 500);
        }
    }

    // Method to delete a comment
    public function deleteComment(Request $request)
    {
        $request->validate([
            'id_comment' => 'required|integer|exists:comments,id',
            'id_publication' => 'required|integer|exists:publications,id',
            'numbercomment' => 'required|integer',
        ]);

        $comment = Comment::find($request->id_comment);

        if ($comment) {
            $comment->delete();

            // Decrement the number of comments for the publication
            Publication::where('id', $request->id_publication)->decrement('numbercomment');

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete the comment'], 500);
        }
    }

    // Method to update the number of comments for a publication
    public function updateNumberOfComments(Request $request)
    {
        $request->validate([
            'id_publication' => 'required|integer|exists:publications,id',
            'number_comment' => 'required|integer',
        ]);

        $publication = Publication::find($request->id_publication);

        if ($publication) {
            $publication->update(['numbercomment' => $request->number_comment+1]);

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Publication not found'], 404);
        }
    }

    // Method to fetch comments for a publication
    public function showComments(Request $request)
    {
        $request->validate([
            'id_publication' => 'required|integer|exists:publications,id',
        ]);
    
        $comments = Comment::with('user:id,firstname,lastname,profile')
                            ->where('id_publication', $request->id_publication)
                            ->whereRaw('LENGTH(text) > 0')
                            ->orderBy('id', 'DESC')
                            ->get();
    
        if ($comments->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No comments found'], 404);
        } else {
            // Transform the comments data to include user details directly within each comment object
            $formattedComments = $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'id_user' => $comment->id_user,
                    'text' => $comment->text,
                    'id_publication' => $comment->id_publication,
                    'number_comment' => $comment->number_comment,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                    'id' => $comment->user->id,
                    'firstname' => $comment->user->firstname,
                    'lastname' => $comment->user->lastname,
                    'profile' => $comment->user->profile,
                ];
            });
    
            return response()->json(['status' => 'success', 'data' => $formattedComments]);
        }
    }
    
}
