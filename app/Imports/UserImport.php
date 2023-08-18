<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;
use App\Models\FileDetail;

class UserImport implements ToModel, WithHeadingRow
{

    private $role, $isUseDefaultPassword, $fileId;

    public function __construct($role, $fileId, $isUseDefaultPassword = false)
    {
        $this->role = $role;
        $this->isUseDefaultPassword = $isUseDefaultPassword;
        $this->fileId = $fileId;
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
        $major          = $row['major'] ?? null;

        print("Insert / Update User: " . $identity . "\n");

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

        $detail = new FileDetail();
        $detail->file_log_id = $this->fileId;
        $detail->status = referenceStatus()::STATUS_IMPORT_ON_PROGRESS;
        $detail->description = "Insert / Update User: $email";
        $detail->save();

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
                $user->password = bcrypt(setting()::getDefaultPasswordValue());
            }


            if ($this->role == 'student') {
                $user->major = $major;
            }

            $user->save();

            $detail->status = FileDetail::STATUS_SUCCESS;
            $detail->save();

            return $user;

        } catch (\Exception $e) {
            $detail->status = FileDetail::STATUS_FAILED;
            $detail->reason = "Error insert/update user $email : $e->getMessage()";
            $detail->save();
            return null;
        }
    }
}
