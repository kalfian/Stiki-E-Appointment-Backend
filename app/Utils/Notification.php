<?php
namespace App\Utils;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

use App\Models\User;
use App\Models\Notification as NotificationModel;

use App\Utils\Alert;

class Notification {
    protected $firebase;

    public function __construct()
    {
        $firebasePath = "/../../credentials/".env("FIREBASE_FILE_NAME");
        $this->firebase = (new Factory)
            ->withServiceAccount(__DIR__.$firebasePath);
    }

    public function send($isRecorded, User $to, $title, $body, $payload = []) {
        try {
            if(is_null($to)) {
                return false;
            }

            $token = $to->fcm_token ?? null;
            if (!is_null($token)) {
                $this->sendNotification($token, $title, $body, $payload);
            }

            if ($isRecorded) {
                $notification = new NotificationModel();
                $notification->title = $title;
                $notification->content = $body;
                $notification->user_id = $to->id;
                $extras = json_encode($payload);
                $notification->extras = $extras;
                $notification->save();
            }

            return true;

        } catch (\Exception $e) {
            Alert::instance()->sendError($e->getMessage());
            return false;
        }

    }

    // $n = new App\Utils\Notification(); $n->sendNotification("fXcnYshwTHSXcMZoNYiPS2:APA91bFgpXKSMQKxpU0kiY3V3sAf4Jau9HhAU1UDQZzpNv2IB1pA7Uvn9D0k1VB0krXIF5CKnmhKhmbDblNsLwVdufyWAwjVqyGa1eFv73tFwii0hYJYVmbKPLUKTVZYSl_aQnfdJ_oh", "test title", "test body", ["testPayload" => "testing"]);

    public function sendNotification($token, $title, $body, $data = [])
    {
        try {

            $data["title"] = $title;
            $data["body"] = $body;

            $messaging = $this->firebase->createMessaging();
            $message = CloudMessage::new()
                ->withTarget('token', $token)
                ->withData($data);

            return $messaging->send($message);
        } catch (\Exception $e) {
            Alert::instance()->sendError($e->getMessage());
            return [
                'message' => 'Gagal mengirim notifikasi',
                'error' => $e->getMessage(),
            ];
        }

    }
}
