<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResetPasswordUser implements FromView
{

    private $userIds;


    public function __construct($userIds)
    {
        $this->userIds = $userIds;
    }

    public function view(): View
    {

        $users = User::whereIn('id', $this->userIds)->get();
        $exportedUsers = [];

        foreach ($users as $key => $user) {
            $password = password_generator();
            $user->password = bcrypt($password);
            $user->save();

            $exportedUsers[] = (object)[
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
            ];
        }

        return view('exports.reset_password_user', [
            'users' => $exportedUsers
        ]);
    }
}
