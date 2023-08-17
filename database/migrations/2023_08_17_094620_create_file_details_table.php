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
        Schema::create('file_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id');

            $table->string('description')->nullable();
            $table->string('reason')->nullable();
            $table->integer('status')->default(-101);

            $table->timestamps();
        });

        // Insert Reference status for file details
        ReferenceStatus::insert([
            [
                'status' => ReferenceStatus::STATUS_IMPORT_SUCCESS,
                'name' => 'Import Success',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => ReferenceStatus::STATUS_IMPORT_FAILED,
                'name' => 'Import Failed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => ReferenceStatus::STATUS_IMPORT_DUPLICATE,
                'name' => 'Import Duplicate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => ReferenceStatus::STATUS_IMPORT_INVALID,
                'name' => 'Import Invalid',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_details');
        // Delete reference status
        ReferenceStatus::whereIn('status', [
            ReferenceStatus::STATUS_IMPORT_SUCCESS,
            ReferenceStatus::STATUS_IMPORT_FAILED,
            ReferenceStatus::STATUS_IMPORT_DUPLICATE,
            ReferenceStatus::STATUS_IMPORT_INVALID,
        ])->delete();
    }
};
