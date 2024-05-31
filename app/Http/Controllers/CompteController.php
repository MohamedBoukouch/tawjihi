<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompteController extends Controller
{
    // Method to delete a user
    public function deleteCompte(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $user_id = $request->input('id');

        $user = User::find($user_id);

        if ($user) {
            $user->delete();

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error'], 500);
        }
    }

    // Method to edit user details
    public function editCompte(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $email = $request->input('email');
        $user_id = $request->input('user_id');

        $user = User::find($user_id);

        if ($user) {
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            $user->email = $email;
            $user->save();

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error'], 500);
        }
    }

    // Method to edit password
    public function editPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'oldpassword' => 'required|string|min:6',
            'newpassword' => 'required|string|min:6',
        ]);

        $user_id = $request->input('user_id');
        $oldpassword = $request->input('oldpassword');
        $newpassword = $request->input('newpassword');

        $user = User::find($user_id);

        if ($user && Hash::check($oldpassword, $user->password)) {
            $user->password = Hash::make($newpassword);
            $user->save();

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error'], 500);
        }
    }
}
