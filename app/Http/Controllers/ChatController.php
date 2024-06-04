<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ChatController extends Controller
{

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'expediteur' => 'required|exists:users,id',
            'destinataire' => 'required|exists:admin,id',
            'user_to_admin' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        $chat = Chat::create([
            'message' => $request->message,
            'date' => Carbon::now()->toDateTimeString(),
            'expediteur' => $request->expediteur,
            'destinataire' => $request->destinataire,
            'user_to_admin' => $request->user_to_admin,
        ]);

        return response()->json(['status' => 'success']);

    }

    public function deleteMessage(Request $request)
    {
        $chat = Chat::find($request->id);

        if (!$chat) {
            return response()->json(['status' => 'error', 'message' => 'Message not found'], 404);
        }

        $chat->delete();
        return response()->json(['status' => 'success'], 200);
    }

    //ACTIVE MESSAGES

    public function activeMessages()
    {
        $messages = Chat::where('is_active', 1)
                            ->where('user_to_admin', 0)
                            ->get();

        $data = $messages->isNotEmpty() ? '1' : '0';
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    //SELECT MESSAGES

    public function selectMessages(Request $request)
    {
        $messages = Chat::where('expediteur', $request->expediteur)
                            ->with('sender:id,firstname,lastname,profile')
                            ->get();

        if ($messages->isEmpty()) {
            return response()->json(['status' => 'error'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $messages], 200);
    }

    //UPDATES MESSAGES

    public function updateMessages(Request $request)
    {
        $messages = Chat::where('expediteur', $request->user_id)->update(['is_active' => 0]);

        return $messages
            ? response()->json(['status' => 'success'], 200)
            : response()->json(['status' => 'error'], 500);
    }
}
