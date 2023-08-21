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
        //
        // Insert initial data
        //
        Artisan::call('db:seed', [
            '--class' => ReferenceStatusSeeder::class,
            '--force' => true
        ]);

        Artisan::call('db:seed', [
            '--class' => RoleSeeder::class,
            '--force' => true
        ]);

        Artisan::call('db:seed', [
            '--class' => SuperadminSeeder::class,
            '--force' => true
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
