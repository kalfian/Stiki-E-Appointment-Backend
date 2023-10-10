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
            // ->withNotification([
            //     'title' => $title,
            //     'body' => $body,
            // ])
            ->withData([
                'key' => 'value', // Add any additional data you want to send
            ]);

        return $messaging->send($message);
    }

    // $n = new App\Utils\Notification(); $id = "fK8pyUK2Rl2CX4yR14E_-F:APA91bHJKvj_ePWIKFs84cfCCSLUG6djbK9pAZCX8FdnUN1wIWcKaEM0A_LCq0boqsfvaEJ5P90IlG2DxSXXUrXjHC9q1i8xzJkoqI6E7XXzcNIlbDZxrPW9IHV6PBPt9EFXeZ1nVdQH"
    //
    // $d = $n->sendNotification($id, "testing", "tester2")
}
