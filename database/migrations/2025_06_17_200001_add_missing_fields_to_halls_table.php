<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('halls', function (Blueprint $table) {
            // Add missing fields if they don't exist
            if (!Schema::hasColumn('halls', 'features')) {
                $table->json('features')->nullable()->after('image');
            }
            if (!Schema::hasColumn('halls', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('features');
            }
        });
    }

    public function down(): void
    {
        Schema::table('halls', function (Blueprint $table) {
            $table->dropColumn([
                'features',
                'is_active'
            ]);
        });
    }
};