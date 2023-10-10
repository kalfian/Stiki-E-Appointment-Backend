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
        try {
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
        } catch (\Exception $e) {
            return [
                'message' => 'Gagal mengirim notifikasi',
                'error' => $e->getMessage(),
            ];
        }

    }

    // $n = new App\Utils\Notification(); $id = "eh5YJVvCQwCeP-ggLFQ_B9:APA91bEYMAMBA9vmex91pC4STGa6vCG_O1DbuBWBcTNzVm11l_U3_kZZmPoJeIwaTv8BWroHA4fb7WCB8tg05Q3anJ4T4V9OGgTGTbfUDNcgXkXBQWiPSoe3CC43LEbsOrINyIAhU3gN"
    //
    // $d = $n->sendNotification($id, "testing", "tester2")
}
