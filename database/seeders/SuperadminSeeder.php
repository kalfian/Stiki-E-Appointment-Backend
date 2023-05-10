<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Hash;

use App\Models\User;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->name = "Super Admin";
        $admin->email = "tester@gmail.com";
        $admin->identity = "SUPERMAN";
        $admin->gender = male();
        $admin->phone_number = "0";
        $admin->email_verified_at = now();
        $admin->password = Hash::make("tester");
        $admin->remember_token = Str::random(10);
        $admin->active_status = referenceStatus()::STATUS_ACTIVE;

        $admin->save();
        $admin->assignRole('super-admin');

        $genders = [male(), female()];

        for($i = 0; $i < 50; $i++) {
            $lecture = new User();
            $lecture->name = "Sample Lecture $i";
            $lecture->email = "lecture$i@mail.com";
            $lecture->identity = "01000$i";
            $lecture->gender = $genders[array_rand($genders)];
            $lecture->phone_number = "08585184341$i";
            $lecture->email_verified_at = now();
            $lecture->password = Hash::make("tester");
            $lecture->remember_token = Str::random(10);
            $lecture->active_status = referenceStatus()::STATUS_ACTIVE;

            $lecture->save();
            $lecture->assignRole('lecture');
        }
    }
}
