<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'profile_photo_path')) {
                    $table->string('profile_photo_path', 2048)->nullable()->after('role');
                }
                if (!Schema::hasColumn('users', 'status')) {
                    $table->string('status')->default('active')->index()->after('profile_photo_path');
                }
                if (!Schema::hasColumn('users', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable()->after('status');
                }
                if (!Schema::hasColumn('users', 'last_login_ip')) {
                    $table->string('last_login_ip')->nullable()->after('last_login_at');
                }
                if (!Schema::hasColumn('users', 'login_attempts')) {
                    $table->unsignedInteger('login_attempts')->default(0)->after('last_login_ip');
                }
                if (!Schema::hasColumn('users', 'password_changed_at')) {
                    $table->timestamp('password_changed_at')->nullable()->after('login_attempts');
                }
            });
        }

        if (Schema::hasTable('halls')) {
            Schema::table('halls', function (Blueprint $table) {
                if (!Schema::hasColumn('halls', 'is_active')) {
                    $table->boolean('is_active')->default(true)->index()->after('features');
                }
            });
        }

        if (Schema::hasTable('packages')) {
            Schema::table('packages', function (Blueprint $table) {
                if (!Schema::hasColumn('packages', 'is_active')) {
                    $table->boolean('is_active')->default(true)->index()->after('highlight');
                }
                if (!Schema::hasColumn('packages', 'min_guests')) {
                    $table->unsignedInteger('min_guests')->nullable()->after('price');
                }
                if (!Schema::hasColumn('packages', 'max_guests')) {
                    $table->unsignedInteger('max_guests')->nullable()->after('min_guests');
                }
                if (!Schema::hasColumn('packages', 'additional_guest_price')) {
                    $table->decimal('additional_guest_price', 10, 2)->default(0)->after('max_guests');
                }
            });
        }

        if (Schema::hasTable('catering_menus')) {
            Schema::table('catering_menus', function (Blueprint $table) {
                if (!Schema::hasColumn('catering_menus', 'price_per_person')) {
                    $table->decimal('price_per_person', 10, 2)->default(0)->after('description');
                }
            });
        }

        if (Schema::hasTable('catering_items')) {
            Schema::table('catering_items', function (Blueprint $table) {
                if (!Schema::hasColumn('catering_items', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
                if (!Schema::hasColumn('catering_items', 'catering_menu_id')) {
                    $table->unsignedBigInteger('catering_menu_id')->nullable()->after('id');
                    $table->index('catering_menu_id');
                }
                if (!Schema::hasColumn('catering_items', 'image')) {
                    $table->string('image')->nullable()->after('price');
                }
            });
        }

        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('bookings', 'wedding_alternative_date1')) {
                    $table->date('wedding_alternative_date1')->nullable()->after('wedding_date');
                }
                if (!Schema::hasColumn('bookings', 'wedding_alternative_date2')) {
                    $table->date('wedding_alternative_date2')->nullable()->after('wedding_alternative_date1');
                }
                if (!Schema::hasColumn('bookings', 'cancelled_at')) {
                    $table->timestamp('cancelled_at')->nullable()->after('deleted_at');
                }
                if (!Schema::hasColumn('bookings', 'cancellation_reason')) {
                    $table->text('cancellation_reason')->nullable()->after('cancelled_at');
                }
                if (!Schema::hasColumn('bookings', 'visit_submitted')) {
                    $table->boolean('visit_submitted')->default(false)->index()->after('visit_time');
                }
                if (!Schema::hasColumn('bookings', 'visit_confirmed')) {
                    $table->boolean('visit_confirmed')->default(false)->index()->after('visit_submitted');
                }
                if (!Schema::hasColumn('bookings', 'visit_confirmed_at')) {
                    $table->timestamp('visit_confirmed_at')->nullable()->after('visit_confirmed');
                }
                if (!Schema::hasColumn('bookings', 'visit_confirmed_by')) {
                    $table->string('visit_confirmed_by')->nullable()->after('visit_confirmed_at');
                }
                if (!Schema::hasColumn('bookings', 'visit_confirmation_notes')) {
                    $table->text('visit_confirmation_notes')->nullable()->after('visit_confirmed_by');
                }
                if (!Schema::hasColumn('bookings', 'advance_payment_required')) {
                    $table->boolean('advance_payment_required')->default(false)->after('visit_confirmation_notes');
                }
                if (!Schema::hasColumn('bookings', 'advance_payment_amount')) {
                    $table->decimal('advance_payment_amount', 10, 2)->default(0)->after('advance_payment_required');
                }
                if (!Schema::hasColumn('bookings', 'advance_payment_paid')) {
                    $table->boolean('advance_payment_paid')->default(false)->index()->after('advance_payment_amount');
                }
                if (!Schema::hasColumn('bookings', 'advance_payment_paid_at')) {
                    $table->timestamp('advance_payment_paid_at')->nullable()->after('advance_payment_paid');
                }
                if (!Schema::hasColumn('bookings', 'advance_payment_method')) {
                    $table->string('advance_payment_method')->nullable()->after('advance_payment_paid_at');
                }
                if (!Schema::hasColumn('bookings', 'advance_payment_notes')) {
                    $table->text('advance_payment_notes')->nullable()->after('advance_payment_method');
                }
                if (!Schema::hasColumn('bookings', 'step5_unlocked')) {
                    $table->boolean('step5_unlocked')->default(false)->after('advance_payment_notes');
                }
                if (!Schema::hasColumn('bookings', 'workflow_step')) {
                    $table->string('workflow_step')->nullable()->index()->after('step5_unlocked');
                }
                if (!Schema::hasColumn('bookings', 'workflow_notes')) {
                    $table->text('workflow_notes')->nullable()->after('workflow_step');
                }
                if (!Schema::hasColumn('bookings', 'visit_rejected')) {
                    $table->boolean('visit_rejected')->default(false)->after('workflow_notes');
                }
                if (!Schema::hasColumn('bookings', 'visit_rejected_at')) {
                    $table->timestamp('visit_rejected_at')->nullable()->after('visit_rejected');
                }
                if (!Schema::hasColumn('bookings', 'visit_rejected_by')) {
                    $table->string('visit_rejected_by')->nullable()->after('visit_rejected_at');
                }
                if (!Schema::hasColumn('bookings', 'visit_rejection_reason')) {
                    $table->text('visit_rejection_reason')->nullable()->after('visit_rejected_by');
                }
            });
        }
    }

    public function down(): void
    {
        // Intentionally non-destructive. These columns are production compatibility fields.
    }
};
