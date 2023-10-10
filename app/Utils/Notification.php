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

    // $n = new App\Utils\Notification(); $id = "fXcnYshwTHSXcMZoNYiPS2:APA91bHM_5F4qhw-1X7GcUdoePMIPAWjMHC6mgO4cSql3YnxFITyqbcDNHph8JE8Y2-Jvz3iX8LJcDtQkEaYPrQXZe0MiiWL6XuIlPJeAHH9XRQ4QqGBPTPtfVmIk2YaZoxo_ZtaYq3T"
    //
    // $d = $n->sendNotification($id, "testing", "tester2")
}
