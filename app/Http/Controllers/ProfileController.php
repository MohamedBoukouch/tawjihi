<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Method to add/update profile image
    public function addProfileImage(Request $request)
    {
        $request->validate([
            'profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $user_id = $request->input('user_id');
        $profile = $request->file('profile');

        // Store the image in the storage (e.g., 'public/profile_images')
        $path = $profile->store();

        $user = User::find($user_id);

        if ($user) {
            // Delete the old profile image if exists
            if ($user->profile) {
                Storage::disk('public')->delete($user->profile);
            }

            // Update the user's profile image path
            $user->profile = $path;
            $user->save();

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error'], 500);
        }
    }

    // Method to fetch profile details
    public function fetchProfile(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer|exists:users,id'
        ]);

        $id_user = $request->input('id_user');

        $user = User::find($id_user);

        if ($user) {
            return response()->json(['status' => 'success', 'data' => $user]);
        } else {
            return response()->json(['status' => 'error'], 500);
        }
    }
}
