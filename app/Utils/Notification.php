<?php
namespace App\Utils;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;


class Notification {
    protected $firebase;

    public function __construct()
    {
        $firebasePath = "/../../credentials/stiki-e-appointment-firebase-adminsdk-f0gpv-4d21a82ed7.json";
        $this->firebase = (new Factory)
            ->withServiceAccount(__DIR__.$firebasePath);
    }

    public function sendNotification($token, $title, $body)
    {
        $messaging = $this->firebase->createMessaging();

        $message = CloudMessage::new()
            ->withTarget('token', $token)
            ->withNotification([
                'title' => $title,
                'body' => $body,
            ])
            ->withData([
                'key' => 'value', // Add any additional data you want to send
            ]);

        $messaging->send($message);
    }

}
