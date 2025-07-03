<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // Add guest capacity fields
            $table->integer('min_guests')->default(50)->after('price');
            $table->integer('max_guests')->default(150)->after('min_guests');
            $table->decimal('additional_guest_price', 8, 2)->default(2500.00)->after('max_guests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['min_guests', 'max_guests', 'additional_guest_price']);
        });
    }
};