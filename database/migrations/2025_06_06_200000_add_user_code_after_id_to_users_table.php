<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // This migration is now disabled to prevent duplicate user_code column errors.
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('user_code', 20)->nullable()->after('id');
        // });
    }
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_code');
        });
    }
};
