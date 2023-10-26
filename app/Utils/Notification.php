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

    public function send(Boolean $isRecorded, User $to, $title, $body, $payload = []) {
        try {
            $token = $to->fcm_token;
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

    public function sendNotification($token, $title, $body, $data = [])
    {
        try {
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
