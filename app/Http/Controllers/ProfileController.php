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
    
        // Generate a random filename for the image
        $new_name = rand() . '.' . $profile->getClientOriginalExtension();
    
        // Move the image to the 'mem' directory
        $profile->move(public_path('/images/profile'), $new_name);
    
        $user = User::find($user_id);
    
        if ($user) {
            // Update the user's profile image path
            $user->profile =  $new_name; // Store relative path
            $user->save();
    
            return response()->json([
                'message' => 'Profile image upload successful',
                'name' => $new_name,
                'status' => 'success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
                'status' => 'error'
            ], 404);
        }
    }

    // Method to fetch profile details
    public function fetchProfile(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id_user' => 'required|integer|exists:users,id'
        ]);
    
        // Retrieve the user ID from the request
        $id_user = $request->input('id_user');
    
        // Find the user by ID
        $user = User::find($id_user);
    
        if ($user) {
            // Return the user data including the password hash
            return response()->json(['status' => 'success', 'data' => $user], 200);
        } else {
            // Return an error response if the user is not found
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }
}
