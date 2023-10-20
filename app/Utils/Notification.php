<?php
namespace App\Utils;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;


class Notification {
    protected $firebase;

    public function __construct()
    {
        $firebasePath = "/../../credentials/".env("FIREBASE_FILE_NAME");
        $this->firebase = (new Factory)
            ->withServiceAccount(__DIR__.$firebasePath);
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
            return [
                'message' => 'Gagal mengirim notifikasi',
                'error' => $e->getMessage(),
            ];
        }

    }

    // $n = new App\Utils\Notification(); $id = "fXcnYshwTHSXcMZoNYiPS2:APA91bFuHO_BQ01GBUW_YX9A9ywhG5gqHwHAlvT9549pXghUu-NrK5c9fsjXSmcWHUoi6vCODHdZsFrIjtWBqUwK3Pg4aUdRC_UOwk2IlhgfDZtAiPJW8Q1jRnWuHPzgUSg097l0cknD"
    //
    // $d = $n->sendNotification($id, "testing", "tester2")
}
