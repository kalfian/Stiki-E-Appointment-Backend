<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\PasswordReset;

use Illuminate\Support\Str;
use Mail;

class SendResetEmailUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        //
        $this->user = User::find($userId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $user = $this->user;

        // Reset Password
        $passwordReset = PasswordReset::where('email', $user->email)->delete();

        $newResetCode = new PasswordReset();
        $newResetCode->email = $user->email;
        $newResetCode->token = Str::random(60);
        $newResetCode->save();

        $data = [
            'user' => $user,
            'resetCode' => $newResetCode->token,
        ];

        Mail::send('emails.reset_password', $data, function ($message) use ($data) {
            $data = (object) $data;
            $message->to($data->user->email, $data->user->name)->subject('Reset Password');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });
    }
}
