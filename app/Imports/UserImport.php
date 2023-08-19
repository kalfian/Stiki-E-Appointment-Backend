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

        $detail = new FileDetail();
        $detail->file_log_id = $this->fileId;
        $detail->status = referenceStatus()::STATUS_IMPORT_ON_PROGRESS;
        $detail->description = "Insert / Update User: $email";
        $detail->save();

        if (strtolower($gender) == 'male') {
            $gender = 1;
        } else {
            $gender = 0;
        }

        if (is_null($identity) || is_null($name) || is_null($email) || is_null($phoneNumber) || is_null($gender) || is_null($status)) {
            $detail->status = referenceStatus()::STATUS_IMPORT_FAILED;
            $detail->description = "Error insert/update user $email : Invalid data";

            $data = [
                "identity" => $identity,
                "name" => $name,
                "email" => $email,
                "phone_number" => $phoneNumber,
                "gender" => $gender,
                "status" => $status,
                "role" => $this->role,
                "major" => $major
            ];
            $detail->extras = json_encode($data);

            $detail->save();
            return null;
        }

        if ($this->role == 'student') {
            if (is_null($major)) {
                $detail->status = referenceStatus()::STATUS_IMPORT_FAILED;
                $detail->description = "Error insert/update user $email : Invalid data";
                $detail->save();
                return null;
            }
        }

        try {
            $user = User::where('identity', $identity)->first();
            if (!$user) {
                $user = new User();
            }

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
            $user->assignRole($this->role);

            $detail->status = referenceStatus()::STATUS_IMPORT_SUCCESS;
            $detail->save();

            return $user;

        } catch (\Exception $e) {
            $detail->status = referenceStatus()::STATUS_IMPORT_FAILED;
            $detail->reason = "Error insert/update user $email";
            $detail->extras = $e->getMessage();
            print($e->getMessage() . "\n");
            $detail->save();
            return null;
        }
    }
}
