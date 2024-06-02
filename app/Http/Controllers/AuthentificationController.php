<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Mail;
use App\Mail\MailNotify;

class AuthentificationController extends Controller
{
    public function test()
    {
        return "hello";
    }
    
    // Login method
    public function login(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required'
        // ]);

        // $email = $request->input('email');
        // $password = $request->input('password');

        // $user = User::where('email', $email)->first();

        // if ($user && Hash::check($password, $user->password)) {
        //     return response()->json(['status' => 'success', 'data' => $user]);
        // } else {
        //     return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        // }
        $data = [
            'subject' => 'cambo',
            'body' => 'Hello'
        ];
        
        try {
            Mail::to('boukouchmohamed7@gmail.com')->send(new MailNotify($data));
            return response()->json(['check ur email']);
        } catch (Exception $e) {
            return response()->json(['Sorry']);
        }
    }

    // Signup method
    public function signup(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $verifycode = rand(9999, 99999);

        $user = User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
            'verifycode' => $verifycode,
            'profile' => 0
        ]);

        if ($user) {
            // Assuming you have a method to send email
            if ($this->sendEmail($email, "Verify Code Taleb", "Verify code $verifycode") == "Success") {
                return response()->json(['status' => 'success', 'email' => 'Email sent successfully']);
            } else {
                return response()->json(['status' => 'error', 'email' => 'Failed to send email'], 500);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'User creation failed'], 500);
        }
    }

    // Verification method
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verifycode' => 'required|string'
        ]);

        $email = $request->input('email');
        $verifycode = $request->input('verifycode');

        $user = User::where('email', $email)->where('verifycode', $verifycode)->first();

        if ($user) {
            return response()->json(['status' => 'success', 'data' => $user]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Verification failed'], 401);
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

    // Method to send "Hello" email to the user
    public function sendHelloEmail($email)
    {
        $subject = "Hello from Our Application";
        $message = "Hello";

        if ($this->sendEmail($email, $subject, $message) == "Success") {
            return response()->json(['status' => 'success', 'message' => 'Hello email sent successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to send Hello email'], 500);
        }
    }

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

    public function mohamed(){
        $data=[
            'subject'=>'cambo',
            'body'=>'Hello'
        ];
        try{
            Mail::to('boukouchmohamed07@gmail.com')->send(new MailNotify($data));
            return response()->json(['check ur email']);
        }catch(Exception $e){
            return response()->json(['Sorry']);
        }
    }
    
}
