<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    static public function notify($titel,$body,$device_key){
        $url="";
        $serverkay="";

        $dataArr=[
            "click_action"=>"Flutter Notification Click",
            "status"=>"doane",
        ];

        $data=[
            "registration_ids"=>[$device_key],
            "notification"=>[
                "titel"=>$titel,
                "body"=>$body,
                "sound"=>"defaut"
            ],
            "data"=>$dataArr,
            "priority"=>"hight"
        ];

        $encodeddata=json_encode($data);
        $header=[
            "Authorization:key" . $serverkey,
            "Content-Type : application/json",
        ];

        $ch=curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch,CURLOPT_HTTP_VERSION, CURLOPT_HTTP_VERSION_1_1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POSIFIELDS, $encodedData);

        $result=curl_exec($ch);

        if($result === FALSE){
            return [
                'message'=>'failed',
                'r'=>$result,
                'success'=>false,
            ];
        }

        curl_close($ch);

        return[
            'message'=>'success',
            'r'=>$result,
            'success'=>'true',
        ];

    }

    public function testqueues(Request $request){
            
    }























    // // Select all notifications
    // public function selectNotifications()
    // {
    //     $notifications = Notification::orderBy('id', 'DESC')->get();
    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $notifications
    //     ]);
    // }

    // // Delete a notification
    // public function deletNotification(Request $request)
    // {
    //     // Validate that id is provided
    //     $request->validate([
    //         'id' => 'required|exists:notifications,id'
    //     ]);

    //     $notification = Notification::find($request->id);

    //     if ($notification) {
    //         $notification->delete();
    //         return response()->json(['status' => 'success']);
    //     } else {
    //         return response()->json(['status' => 'error']);
    //     }
    // }

    // // Get notification by publication ID
    // public function getByPublication($publication_id)
    // {
    //     $notifications = Notification::where('publication_id', $publication_id)->get();

    //     if ($notifications->count() > 0) {
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $notifications
    //         ]);
    //     } else {
    //         return response()->json(['status' => 'error']);
    //     }
    // }

    // // Update notification status to inactive
    // public function updateStatus()
    // {
    //     $updated = Notification::where('active', 1)->update(['active' => 0]);

    //     if ($updated) {
    //         return response()->json(['status' => 'success']);
    //     } else {
    //         return response()->json(['status' => 'error']);
    //     }
    // }

    // //Check Status
    // public function checkActiveNotifications()
    // {
    //     $count = Notification::where('active', 1)->count();

    //     if ($count > 0) {
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => '1'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => '0'
    //         ]);
    //     }
    // }
}
