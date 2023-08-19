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
        Schema::table('file_details', function (Blueprint $table) {
            //
            $table->longText('extras')->nullable()->after('file_log_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('file_details', function (Blueprint $table) {
            //
            $table->dropColumn('extras');
        });

    }
};
