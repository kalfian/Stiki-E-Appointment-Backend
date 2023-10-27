<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

use App\Utils\Notification;


class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user, $isRecorded, $title, $body, $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $isRecorded, $title, $body, $payload = [])
    {
        //
        $this->user = User::find($userId);
        $this->isRecorded = $isRecorded;
        $this->title = $title;
        $this->body = $body;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $notification = new Notification();
        $notification->send($this->isRecorded, $this->user, $this->title, $this->body, $this->payload);
    }
}
