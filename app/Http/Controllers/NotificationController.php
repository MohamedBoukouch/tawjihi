<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Select all notifications
    public function selectNotifications()
    {
        $notifications = Notification::orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => 'success',
            'data' => $notifications
        ]);
    }

    // Delete a notification
    public function deletNotification(Request $request)
    {
        // Validate that id is provided
        $request->validate([
            'id' => 'required|exists:notifications,id'
        ]);

        $notification = Notification::find($request->id);

        if ($notification) {
            $notification->delete();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    // Get notification by publication ID
    public function getByPublication($publication_id)
    {
        $notifications = Notification::where('publication_id', $publication_id)->get();

        if ($notifications->count() > 0) {
            return response()->json([
                'status' => 'success',
                'data' => $notifications
            ]);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    // Update notification status to inactive
    public function updateStatus()
    {
        $updated = Notification::where('active', 1)->update(['active' => 0]);

        if ($updated) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    //Check Status
    public function checkActiveNotifications()
    {
        $count = Notification::where('active', 1)->count();

        if ($count > 0) {
            return response()->json([
                'status' => 'success',
                'data' => '1'
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => '0'
            ]);
        }
    }
}
