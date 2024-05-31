<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator; // Correct import


class ResetPasswordController extends Controller
{
    // Method to check email and send verify code
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->input('email');
        $verifycode = rand(9999, 99999);

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->verifycode = $verifycode;
            $user->save();

            if ($this->sendEmail($email, "Verify Code Taleb", "Verify code $verifycode") == "Success") {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Failed to send email']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 500);
        }
    }

    // Method to reset password
    public function resetPassword(Request $request)
    {
        

        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6'
        ]);
        // $request->validate([
        //     'email' => 'required|email|exists:users,email',
        //     'password' => 'required|string|min:6|confirmed'
        // ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        // Find the user by email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update the user's password
            $user->password = Hash::make($password);
            $user->save();


            return response()->json(['status' => 'success', 'data' => $user]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }

    // Method to verify account
    public function verifyCompte(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'verifycode' => 'required|string'
        ]);

        $email = $request->input('email');
        $verifycode = $request->input('verifycode');

        $user = User::where('email', $email)->where('verifycode', $verifycode)->first();

        if ($user) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Verification failed'], 500);
        }
    }

    // Placeholder for the sendEmail method
    private function sendEmail($to, $subject, $message)
    {
        try {
            Mail::raw($message, function($mail) use ($to, $subject) {
                $mail->to($to)
                     ->subject($subject);
            });
            return "Success";
        } catch (\Exception $e) {
            return "Failed";
        }
    }
}
