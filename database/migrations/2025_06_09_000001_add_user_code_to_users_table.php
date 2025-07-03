<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Duplicate user_code column, already exists in users table. This migration is now disabled.
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('user_code', 20)->nullable()->unique()->after('id');
        // });
    }

    public function down()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn('user_code');
        // });
    }
};
