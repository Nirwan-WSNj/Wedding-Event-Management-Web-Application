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
            // Add features column if it doesn't exist
            if (!Schema::hasColumn('packages', 'features')) {
                $table->json('features')->nullable()->after('image');
            }
            
            // Add highlight column if it doesn't exist
            if (!Schema::hasColumn('packages', 'highlight')) {
                $table->boolean('highlight')->default(false)->after('features');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'features')) {
                $table->dropColumn('features');
            }
            if (Schema::hasColumn('packages', 'highlight')) {
                $table->dropColumn('highlight');
            }
        });
    }
};