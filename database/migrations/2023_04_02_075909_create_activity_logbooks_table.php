<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logbooks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')->constrained('activities');
            $table->foreignId('user_id')->constrained('users');
            $table->text('description');
            $table->string('logbook_proof')->nullable();
            $table->text('problem')->nullable();
            $table->text('lecture_comment')->nullable();
            $table->dateTime('date');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logbooks');
    }
};
