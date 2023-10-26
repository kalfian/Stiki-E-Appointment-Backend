<?php
namespace App\Utils;

use Spatie\DiscordAlerts\Facades\DiscordAlert;


class Alert {
    protected $firebase;

    public function __construct()
    {

    }

    public static function instance() {
        return new Alert();
    }

    public function send($message)
    {
        try {
            DiscordAlert::send($message);
        } catch (\Exception $e) {
            return [
                'message' => 'Gagal mengirim notifikasi',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function sendError($message) {
        DiscordAlert::message("Error Catch!", [
            [
                'description' => $message,
                'color' => '#E77625'
            ]
        ]);
    }
}
