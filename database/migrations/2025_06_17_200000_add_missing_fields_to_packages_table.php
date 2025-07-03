<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // Add missing fields if they don't exist
            if (!Schema::hasColumn('packages', 'min_guests')) {
                $table->integer('min_guests')->default(50)->after('price');
            }
            if (!Schema::hasColumn('packages', 'max_guests')) {
                $table->integer('max_guests')->default(500)->after('min_guests');
            }
            if (!Schema::hasColumn('packages', 'additional_guest_price')) {
                $table->decimal('additional_guest_price', 8, 2)->default(0)->after('max_guests');
            }
            if (!Schema::hasColumn('packages', 'features')) {
                $table->json('features')->nullable()->after('additional_guest_price');
            }
            if (!Schema::hasColumn('packages', 'highlight')) {
                $table->boolean('highlight')->default(false)->after('features');
            }
            if (!Schema::hasColumn('packages', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('highlight');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'min_guests',
                'max_guests', 
                'additional_guest_price',
                'features',
                'highlight',
                'is_active'
            ]);
        });
    }
};