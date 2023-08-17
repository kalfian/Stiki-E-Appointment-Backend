<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;

class UserImport implements ToModel, WithHeadingRow
{

    private $role, $isUseDefaultPassword;

    public function __construct($role, $isUseDefaultPassword = false)
    {
        $this->role = $role;
        $this->isUseDefaultPassword = $isUseDefaultPassword;
    }

    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        $identity       = $row['identity'];
        $name           = $row['name'];
        $email          = $row['email'];
        $phoneNumber    = $row['phone_number'];
        $gender         = $row['gender'];
        $status         = $row['status'];
        $major          = $row['major'];

        if (strtolower($gender) == 'male') {
            $gender = 1;
        } else {
            $gender = 0;
        }

        if (!$identity || !$name || !$email || !$phoneNumber || !$gender || !$status) {
            return null;
        }

        if ($this->role == 'student') {
            if (!$major) {
                return null;
            }
        }

        try {
            $user = User::where('identity', $identity)->first();
            if ($user) {
                return null;
            }

            $user = new User();

            $user->identity         = $identity;
            $user->name             = $name;
            $user->email            = $email;
            $user->phone_number     = $phoneNumber;
            $user->gender           = $gender;
            $user->active_status    = $status;
            $user->password         = bcrypt(passwordGenerator());
            if ($this->isUseDefaultPassword) {
                $user->password = bcrypt('12345678');
            }


            if ($this->role == 'student') {
                $user->major = $major;
            }

            $user->save();

            return $user;

        } catch (\Exception $e) {
            return null;
        }
    }
}
