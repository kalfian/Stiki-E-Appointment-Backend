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
        Schema::table('file_logs', function (Blueprint $table) {
            //
            $table->boolean('use_default_password')->after('import_type')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_logs', function (Blueprint $table) {
            //
            $table->dropColumn('use_default_password');
        });
    }
};
