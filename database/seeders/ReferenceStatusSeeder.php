<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ReferenceStatus;

class ReferenceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $status = new ReferenceStatus();
        $status->status = referenceStatus()::STATUS_ACTIVE;
        $status->name = "Active";
        $status->save();

        $status = new ReferenceStatus();
        $status->name = "Inactive";
        $status->status = referenceStatus()::STATUS_INACTIVE;
        $status->save();

        $status = new ReferenceStatus();
        $status->name = "Banned";
        $status->status = referenceStatus()::STATUS_BANNED;
        $status->save();

        $status = new ReferenceStatus();
        $status->name = "Draft";
        $status->status = referenceStatus()::STATUS_DRAFT;
        $status->save();
    }
}
