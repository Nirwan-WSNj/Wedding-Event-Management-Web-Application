<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('additional_services', function (Blueprint $table) {
            $table->string('image', 255)->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('additional_services', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
