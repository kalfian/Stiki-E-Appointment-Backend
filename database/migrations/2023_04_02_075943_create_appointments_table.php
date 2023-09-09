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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('description');
            $table->string('location');
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('lecture_id')->constrained('users');
            $table->foreignId('activity_id')->constrained('activities');
            $table->integer('status')->default(0);

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
        Schema::dropIfExists('appointments');
    }
};
