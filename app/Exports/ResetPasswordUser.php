<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResetPasswordUser implements FromView
{

    private $userIds, $useDefaultPassword;


    public function __construct($userIds, $useDefaultPassword)
    {
        $this->userIds = $userIds;
        $this->useDefaultPassword = $useDefaultPassword;
    }

    public function view(): View
    {

        $users = User::whereIn('id', $this->userIds)->get();
        $exportedUsers = [];

        foreach ($users as $key => $user) {
            $password = password_generator();
            if ($this->useDefaultPassword == 1) $password = setting()::getDefaultPasswordValue();

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
