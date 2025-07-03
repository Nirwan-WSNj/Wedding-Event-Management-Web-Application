<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if packages table exists and has string ID
        if (Schema::hasTable('packages')) {
            $columns = Schema::getColumnListing('packages');
            $idColumn = DB::select("SHOW COLUMNS FROM packages WHERE Field = 'id'")[0] ?? null;
            
            // If ID is string type, we need to recreate the table
            if ($idColumn && str_contains($idColumn->Type, 'varchar')) {
                // Backup existing data
                $existingPackages = DB::table('packages')->get();
                
                // Drop the table and recreate with proper structure
                Schema::dropIfExists('packages');
                
                Schema::create('packages', function (Blueprint $table) {
                    $table->id(); // Auto-incrementing integer ID
                    $table->string('name');
                    $table->text('description')->nullable();
                    $table->decimal('price', 12, 2);
                    $table->string('image')->nullable();
                    $table->json('features')->nullable();
                    $table->boolean('highlight')->default(false);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                    $table->softDeletes();
                });
                
                // Restore data with new auto-incrementing IDs
                foreach ($existingPackages as $package) {
                    DB::table('packages')->insert([
                        'name' => $package->name,
                        'description' => $package->description ?? '',
                        'price' => $package->price ?? 0,
                        'image' => $package->image,
                        'features' => $package->features ?? json_encode([]),
                        'highlight' => $package->highlight ?? false,
                        'is_active' => $package->is_active ?? true,
                        'created_at' => $package->created_at ?? now(),
                        'updated_at' => $package->updated_at ?? now(),
                    ]);
                }
            } else {
                // Table exists with proper ID, just add missing columns
                if (!Schema::hasColumn('packages', 'features')) {
                    Schema::table('packages', function (Blueprint $table) {
                        $table->json('features')->nullable()->after('image');
                    });
                }
                
                if (!Schema::hasColumn('packages', 'highlight')) {
                    Schema::table('packages', function (Blueprint $table) {
                        $table->boolean('highlight')->default(false)->after('features');
                    });
                }
                
                if (!Schema::hasColumn('packages', 'is_active')) {
                    Schema::table('packages', function (Blueprint $table) {
                        $table->boolean('is_active')->default(true)->after('highlight');
                    });
                }
                
                // Ensure price column is not nullable
                if (Schema::hasColumn('packages', 'price')) {
                    Schema::table('packages', function (Blueprint $table) {
                        $table->decimal('price', 12, 2)->nullable(false)->change();
                    });
                }
            }
        } else {
            // Create new packages table
            Schema::create('packages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 12, 2);
                $table->string('image')->nullable();
                $table->json('features')->nullable();
                $table->boolean('highlight')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
};