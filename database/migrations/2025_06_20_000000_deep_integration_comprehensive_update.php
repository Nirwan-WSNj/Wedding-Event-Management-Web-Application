<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for deep integration comprehensive update.
     */
    public function up(): void
    {
        // Enhanced Packages Table for Deep Integration
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'compatible_halls')) {
                $table->json('compatible_halls')->nullable()->after('features');
            }
            if (!Schema::hasColumn('packages', 'min_guests')) {
                $table->integer('min_guests')->default(50)->after('price');
            }
            if (!Schema::hasColumn('packages', 'max_guests')) {
                $table->integer('max_guests')->default(150)->after('min_guests');
            }
            if (!Schema::hasColumn('packages', 'additional_guest_price')) {
                $table->decimal('additional_guest_price', 10, 2)->default(2500.00)->after('max_guests');
            }
            if (!Schema::hasColumn('packages', 'manager_approval_required')) {
                $table->boolean('manager_approval_required')->default(false)->after('additional_guest_price');
            }
            if (!Schema::hasColumn('packages', 'seasonal_pricing')) {
                $table->json('seasonal_pricing')->nullable()->after('manager_approval_required');
            }
            if (!Schema::hasColumn('packages', 'booking_count')) {
                $table->integer('booking_count')->default(0)->after('seasonal_pricing');
            }
            if (!Schema::hasColumn('packages', 'total_revenue')) {
                $table->decimal('total_revenue', 15, 2)->default(0)->after('booking_count');
            }
        });

        // Enhanced Halls Table for Deep Integration
        Schema::table('halls', function (Blueprint $table) {
            if (!Schema::hasColumn('halls', 'seasonal_pricing')) {
                $table->json('seasonal_pricing')->nullable()->after('price');
            }
            if (!Schema::hasColumn('halls', 'availability_calendar')) {
                $table->json('availability_calendar')->nullable()->after('seasonal_pricing');
            }
            if (!Schema::hasColumn('halls', 'manager_id')) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('availability_calendar');
                $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('halls', 'maintenance_schedule')) {
                $table->json('maintenance_schedule')->nullable()->after('manager_id');
            }
            if (!Schema::hasColumn('halls', 'booking_count')) {
                $table->integer('booking_count')->default(0)->after('maintenance_schedule');
            }
            if (!Schema::hasColumn('halls', 'total_revenue')) {
                $table->decimal('total_revenue', 15, 2)->default(0)->after('booking_count');
            }
            if (!Schema::hasColumn('halls', 'last_booking_date')) {
                $table->date('last_booking_date')->nullable()->after('total_revenue');
            }
        });

        // Enhanced Bookings Table for Deep Integration
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'manager_approval_status')) {
                $table->enum('manager_approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('bookings', 'manager_approval_notes')) {
                $table->text('manager_approval_notes')->nullable()->after('manager_approval_status');
            }
            if (!Schema::hasColumn('bookings', 'manager_approved_by')) {
                $table->unsignedBigInteger('manager_approved_by')->nullable()->after('manager_approval_notes');
                $table->foreign('manager_approved_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('bookings', 'manager_approved_at')) {
                $table->timestamp('manager_approved_at')->nullable()->after('manager_approved_by');
            }
            if (!Schema::hasColumn('bookings', 'payment_confirmation_status')) {
                $table->enum('payment_confirmation_status', ['pending', 'confirmed', 'failed'])->default('pending')->after('manager_approved_at');
            }
            if (!Schema::hasColumn('bookings', 'payment_confirmed_by')) {
                $table->unsignedBigInteger('payment_confirmed_by')->nullable()->after('payment_confirmation_status');
                $table->foreign('payment_confirmed_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('bookings', 'payment_confirmed_at')) {
                $table->timestamp('payment_confirmed_at')->nullable()->after('payment_confirmed_by');
            }
            if (!Schema::hasColumn('bookings', 'step_completion_status')) {
                $table->json('step_completion_status')->nullable()->after('payment_confirmed_at');
            }
            if (!Schema::hasColumn('bookings', 'booking_progression_log')) {
                $table->json('booking_progression_log')->nullable()->after('step_completion_status');
            }
            if (!Schema::hasColumn('bookings', 'estimated_total_cost')) {
                $table->decimal('estimated_total_cost', 15, 2)->nullable()->after('booking_progression_log');
            }
            if (!Schema::hasColumn('bookings', 'final_total_cost')) {
                $table->decimal('final_total_cost', 15, 2)->nullable()->after('estimated_total_cost');
            }
        });

        // Manager Call Logs Table for Customer Communication Tracking
        if (!Schema::hasTable('manager_call_logs')) {
            Schema::create('manager_call_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('booking_id');
                $table->unsignedBigInteger('manager_id')->nullable();
                $table->enum('call_status', ['successful', 'no_answer', 'busy', 'invalid_number', 'customer_declined']);
                $table->text('call_notes')->nullable();
                $table->timestamp('call_attempted_at');
                $table->string('customer_phone', 20)->nullable();
                $table->string('customer_name')->nullable();
                $table->integer('call_duration_seconds')->nullable();
                $table->enum('call_outcome', ['visit_approved', 'visit_rejected', 'reschedule_requested', 'no_decision'])->nullable();
                $table->json('call_metadata')->nullable(); // Additional call data
                $table->timestamps();

                $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
                $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['booking_id', 'call_attempted_at']);
                $table->index(['manager_id', 'call_status']);
            });
        }

        // Package Hall Compatibility Table for Deep Integration
        if (!Schema::hasTable('package_hall_compatibility')) {
            Schema::create('package_hall_compatibility', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('package_id');
                $table->unsignedBigInteger('hall_id');
                $table->integer('compatibility_score')->default(100); // 0-100 compatibility rating
                $table->decimal('special_pricing', 10, 2)->nullable(); // Special pricing for this combination
                $table->json('special_features')->nullable(); // Additional features for this combination
                $table->boolean('is_recommended')->default(false); // Recommended combination
                $table->text('compatibility_notes')->nullable();
                $table->timestamps();

                $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
                $table->foreign('hall_id')->references('id')->on('halls')->onDelete('cascade');
                $table->unique(['package_id', 'hall_id'], 'unique_package_hall');
                $table->index(['compatibility_score', 'is_recommended']);
            });
        }

        // Booking Status History for Complete Audit Trail
        if (!Schema::hasTable('booking_status_history')) {
            Schema::create('booking_status_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('booking_id');
                $table->string('previous_status')->nullable();
                $table->string('new_status');
                $table->unsignedBigInteger('changed_by')->nullable(); // User who made the change
                $table->text('change_reason')->nullable();
                $table->json('change_metadata')->nullable(); // Additional change data
                $table->timestamp('changed_at');
                $table->timestamps();

                $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
                $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['booking_id', 'changed_at']);
                $table->index(['new_status', 'changed_at']);
            });
        }

        // Manager Notifications for Real-time Updates
        if (!Schema::hasTable('manager_notifications')) {
            Schema::create('manager_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('manager_id');
                $table->string('notification_type'); // visit_request, payment_pending, booking_update, etc.
                $table->string('title');
                $table->text('message');
                $table->json('notification_data')->nullable(); // Related data (booking_id, etc.)
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->boolean('is_actionable')->default(false); // Requires manager action
                $table->string('action_url')->nullable(); // URL for action
                $table->timestamp('expires_at')->nullable(); // Notification expiry
                $table->timestamps();

                $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['manager_id', 'is_read', 'priority']);
                $table->index(['notification_type', 'created_at']);
                $table->index(['is_actionable', 'expires_at']);
            });
        }

        // System Integration Logs for Monitoring
        if (!Schema::hasTable('system_integration_logs')) {
            Schema::create('system_integration_logs', function (Blueprint $table) {
                $table->id();
                $table->string('integration_type'); // package_update, booking_progression, manager_approval, etc.
                $table->string('source_component'); // admin_dashboard, booking_system, manager_dashboard
                $table->string('target_component'); // booking_system, manager_dashboard, customer_interface
                $table->json('integration_data'); // Data being synchronized
                $table->enum('status', ['success', 'failed', 'partial'])->default('success');
                $table->text('error_message')->nullable();
                $table->integer('processing_time_ms')->nullable(); // Performance monitoring
                $table->unsignedBigInteger('triggered_by')->nullable(); // User who triggered the integration
                $table->timestamps();

                $table->foreign('triggered_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['integration_type', 'status', 'created_at']);
                $table->index(['source_component', 'target_component']);
                $table->index(['status', 'processing_time_ms']);
            });
        }

        // Real-time Synchronization Queue
        if (!Schema::hasTable('sync_queue')) {
            Schema::create('sync_queue', function (Blueprint $table) {
                $table->id();
                $table->string('sync_type'); // package_update, hall_availability, booking_status, etc.
                $table->json('sync_data'); // Data to be synchronized
                $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
                $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
                $table->integer('retry_count')->default(0);
                $table->integer('max_retries')->default(3);
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamps();

                $table->index(['status', 'priority', 'scheduled_at']);
                $table->index(['sync_type', 'status']);
                $table->index(['retry_count', 'max_retries']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new tables
        Schema::dropIfExists('sync_queue');
        Schema::dropIfExists('system_integration_logs');
        Schema::dropIfExists('manager_notifications');
        Schema::dropIfExists('booking_status_history');
        Schema::dropIfExists('package_hall_compatibility');
        Schema::dropIfExists('manager_call_logs');

        // Remove columns from existing tables
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['payment_confirmed_by']);
            $table->dropForeign(['manager_approved_by']);
            $table->dropColumn([
                'manager_approval_status',
                'manager_approval_notes',
                'manager_approved_by',
                'manager_approved_at',
                'payment_confirmation_status',
                'payment_confirmed_by',
                'payment_confirmed_at',
                'step_completion_status',
                'booking_progression_log',
                'estimated_total_cost',
                'final_total_cost'
            ]);
        });

        Schema::table('halls', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn([
                'seasonal_pricing',
                'availability_calendar',
                'manager_id',
                'maintenance_schedule',
                'booking_count',
                'total_revenue',
                'last_booking_date'
            ]);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'compatible_halls',
                'min_guests',
                'max_guests',
                'additional_guest_price',
                'manager_approval_required',
                'seasonal_pricing',
                'booking_count',
                'total_revenue'
            ]);
        });
    }
};