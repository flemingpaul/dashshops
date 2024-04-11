<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\LoginToken;
use App\Models\Notification;
use App\Models\Retailer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NotificationsController extends Controller
{
    protected $notification;
    public function __construct()
    {
        $this->notification = Firebase::messaging();
    }

    public function setNotification(Notification $notif)
    {

        $notif->save();
        $app_setting = AppSetting::where("user_id", $notif->user_id)->first();

        if ($app_setting) {
            if ($app_setting->push_notification ==  0) {

                return;
            }
        }
        $loginTokens = LoginToken::where('user_id', $notif->user_id)->get();
        $deviceTokens = [];
        foreach ($loginTokens as $token) {
            array_push($deviceTokens, $token->device_token);
        }
        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => $notif->title,
                'body' => $notif->content,
            ],
            "data" => [
                "detail" => $notif->id
            ]
        ]); // Any instance of Kreait\Messaging\Message
        try {
            $sendReport = $this->notification->sendMulticast($message, $deviceTokens);
            return response()->json([
                "message" => "Notification Sent",
                "data" => $sendReport
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Notification Not Sent",
                "data" => $e->getMessage()
            ]);
        }
    }
    function getNotifications($user_id)
    {
        $notifications = Notification::where(['user_id' => $user_id, 'trash' => false])->orderBy("created_at", "desc")->get();
        return response()->json([
            "message" => "Notifications Fetched Successfully",
            "data" => $notifications
        ]);
    }
    function markAsRead($id)
    {
        $notification = Notification::findorfail($id);
        $notification->has_read = true;
        $notification->save();
        return response()->json([
            "message" => "Notification Marked as Read Successfully",
            "data" => $notification
        ]);
    }
    function markAllAsRead($user_id)
    {
        $notifications = Notification::where('user_id', $user_id)->get();
        foreach ($notifications as $notification) {
            $notification->has_read = true;
            $notification->save();
        }
        return response()->json([
            "message" => "All Notifications Marked as Read Successfully",
            "data" => $notifications
        ]);
    }
    function getUnreadCount($user_id)
    {
        $notifications = Notification::where('user_id', $user_id)->where(['has_read' => false, 'trash' => false])->get();
        return response()->json([
            "message" => "Unread Notifications Fetched Successfully",
            "data" => count($notifications)
        ]);
    }
    function trashNotification($id)
    {
        $notification = Notification::findorfail($id);
        $notification->trash = true;
        $notification->save();
        return response()->json([
            "message" => "Notification Deleted Successfully",
            "data" => $notification
        ]);
    }
    public function testNotificatiion($user_id)
    {
        $loginTokens = LoginToken::where('user_id', $user_id)->get();
        $deviceTokens = [];
        foreach ($loginTokens as $token) {
            array_push($deviceTokens, $token->device_token);
        }
        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => "Test Title",
                'body' => 'Test Body',

            ],
            "data" => [
                "detail" => "Wth the fuck"
            ]
        ]); // Any instance of Kreait\Messaging\Message

        $sendReport = $this->notification->sendMulticast($message, $deviceTokens);
        return response()->json([
            "message" => "Notification Sent",
            "data" => $sendReport
        ]);
    }
}
