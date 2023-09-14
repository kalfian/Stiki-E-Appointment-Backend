<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\ReferenceStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $status = new ReferenceStatus();
        $status->status = referenceStatus()::STATUS_APPOINTMENT_PENDING_ID;
        $status->name = "Menunggu Persetujuan";
        $status->save();

        $status = new ReferenceStatus();
        $status->status = referenceStatus()::STATUS_APPOINTMENT_ACCEPTED_ID;
        $status->name = "Diterima";
        $status->save();

        $status = new ReferenceStatus();
        $status->status = referenceStatus()::STATUS_APPOINTMENT_REJECTED_ID;
        $status->name = "Ditolak";
        $status->save();

        $status = new ReferenceStatus();
        $status->status = referenceStatus()::STATUS_APPOINTMENT_CANCELED_ID;
        $status->name = "Dibatalkan";
        $status->save();

        $status = new ReferenceStatus();
        $status->status = referenceStatus()::STATUS_APPOINTMENT_DONE_ID;
        $status->name = "Selesai";
        $status->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        ReferenceStatus::where('status', referenceStatus()::STATUS_APPOINTMENT_PENDING_ID)->delete();
        ReferenceStatus::where('status', referenceStatus()::STATUS_APPOINTMENT_ACCEPTED_ID)->delete();
        ReferenceStatus::where('status', referenceStatus()::STATUS_APPOINTMENT_REJECTED_ID)->delete();
        ReferenceStatus::where('status', referenceStatus()::STATUS_APPOINTMENT_CANCELED_ID)->delete();
        ReferenceStatus::where('status', referenceStatus()::STATUS_APPOINTMENT_DONE_ID)->delete();
    }
};
