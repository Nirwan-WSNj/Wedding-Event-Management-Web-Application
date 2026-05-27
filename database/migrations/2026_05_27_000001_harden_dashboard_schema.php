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
                $this->addString($table, 'users', 'phone');
                $this->addString($table, 'users', 'profile_photo_path', 2048);
                $this->addString($table, 'users', 'user_code', 50);
                $this->addString($table, 'users', 'status', 255, 'active');
                $this->addTimestamp($table, 'users', 'last_login_at');
                $this->addString($table, 'users', 'last_login_ip');
                $this->addUnsignedInteger($table, 'users', 'login_attempts', 0);
                $this->addTimestamp($table, 'users', 'password_changed_at');
            });
        }

        if (Schema::hasTable('halls')) {
            Schema::table('halls', function (Blueprint $table) {
                $this->addJson($table, 'halls', 'features');
                $this->addBoolean($table, 'halls', 'is_active', true);
            });
        }

        if (Schema::hasTable('packages')) {
            Schema::table('packages', function (Blueprint $table) {
                $this->addUnsignedInteger($table, 'packages', 'min_guests');
                $this->addUnsignedInteger($table, 'packages', 'max_guests');
                $this->addDecimal($table, 'packages', 'additional_guest_price', 10, 2, 0);
                $this->addBoolean($table, 'packages', 'is_active', true);
                $this->addBoolean($table, 'packages', 'manager_approval_required', true);
                $this->addJson($table, 'packages', 'features');
                $this->addJson($table, 'packages', 'compatible_halls');
                $this->addJson($table, 'packages', 'seasonal_pricing');
            });
        }

        if (Schema::hasTable('wedding_types')) {
            Schema::table('wedding_types', function (Blueprint $table) {
                $this->addString($table, 'wedding_types', 'image');
                $this->addBoolean($table, 'wedding_types', 'is_active', true);
            });
        }

        if (Schema::hasTable('decorations')) {
            Schema::table('decorations', function (Blueprint $table) {
                $this->addString($table, 'decorations', 'style');
                $this->addBoolean($table, 'decorations', 'is_active', true);
            });
        }

        if (Schema::hasTable('additional_services')) {
            Schema::table('additional_services', function (Blueprint $table) {
                $this->addBoolean($table, 'additional_services', 'is_active', true);
            });
        }

        if (Schema::hasTable('catering_menus')) {
            Schema::table('catering_menus', function (Blueprint $table) {
                $this->addDecimal($table, 'catering_menus', 'price_per_person', 10, 2, 0);
                $this->addBoolean($table, 'catering_menus', 'is_active', true);
                $this->addUnsignedInteger($table, 'catering_menus', 'minimum_guests', 10);
                $this->addUnsignedInteger($table, 'catering_menus', 'maximum_guests');
            });
        }

        if (Schema::hasTable('catering_items')) {
            Schema::table('catering_items', function (Blueprint $table) {
                $this->addUnsignedBigInteger($table, 'catering_items', 'menu_id');
                $this->addText($table, 'catering_items', 'description');
                $this->addString($table, 'catering_items', 'category');
                $this->addJson($table, 'catering_items', 'dietary_info');
                $this->addString($table, 'catering_items', 'image');
            });
        }

        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $this->addString($table, 'bookings', 'hall_name');
                $this->addString($table, 'bookings', 'package_name');
                $this->addDecimal($table, 'bookings', 'package_price', 12, 2, 0);
                $this->addDecimal($table, 'bookings', 'total_amount', 12, 2, 0);
                $this->addDate($table, 'bookings', 'wedding_alternative_date1');
                $this->addDate($table, 'bookings', 'wedding_alternative_date2');
                $this->addTimestamp($table, 'bookings', 'cancelled_at');
                $this->addText($table, 'bookings', 'cancellation_reason');
                $this->addText($table, 'bookings', 'customization_decorations_additional');
                $this->addText($table, 'bookings', 'customization_catering_custom');
                $this->addText($table, 'bookings', 'customization_additional_services_selected');
                $this->addBoolean($table, 'bookings', 'visit_submitted', false);
                $this->addBoolean($table, 'bookings', 'visit_confirmed', false);
                $this->addTimestamp($table, 'bookings', 'visit_confirmed_at');
                $this->addString($table, 'bookings', 'visit_confirmed_by');
                $this->addText($table, 'bookings', 'visit_confirmation_notes');
                $this->addBoolean($table, 'bookings', 'advance_payment_required', false);
                $this->addDecimal($table, 'bookings', 'advance_payment_amount', 10, 2, 0);
                $this->addBoolean($table, 'bookings', 'advance_payment_paid', false);
                $this->addTimestamp($table, 'bookings', 'advance_payment_paid_at');
                $this->addString($table, 'bookings', 'advance_payment_method');
                $this->addText($table, 'bookings', 'advance_payment_notes');
                $this->addBoolean($table, 'bookings', 'step5_unlocked', false);
                $this->addString($table, 'bookings', 'workflow_step');
                $this->addText($table, 'bookings', 'workflow_notes');
                $this->addBoolean($table, 'bookings', 'visit_rejected', false);
                $this->addTimestamp($table, 'bookings', 'visit_rejected_at');
                $this->addString($table, 'bookings', 'visit_rejected_by');
                $this->addText($table, 'bookings', 'visit_rejection_reason');
            });
        }
    }

    public function down(): void
    {
        // Non-destructive: these are compatibility columns for existing demo/prod databases.
    }

    private function addString(Blueprint $table, string $tableName, string $column, int $length = 255, ?string $default = null): void
    {
        if (Schema::hasColumn($tableName, $column)) {
            return;
        }
        $definition = $table->string($column, $length)->nullable();
        if ($default !== null) {
            $definition->default($default);
        }
    }

    private function addText(Blueprint $table, string $tableName, string $column): void
    {
        if (!Schema::hasColumn($tableName, $column)) {
            $table->text($column)->nullable();
        }
    }

    private function addJson(Blueprint $table, string $tableName, string $column): void
    {
        if (!Schema::hasColumn($tableName, $column)) {
            $table->json($column)->nullable();
        }
    }

    private function addTimestamp(Blueprint $table, string $tableName, string $column): void
    {
        if (!Schema::hasColumn($tableName, $column)) {
            $table->timestamp($column)->nullable();
        }
    }

    private function addDate(Blueprint $table, string $tableName, string $column): void
    {
        if (!Schema::hasColumn($tableName, $column)) {
            $table->date($column)->nullable();
        }
    }

    private function addBoolean(Blueprint $table, string $tableName, string $column, bool $default): void
    {
        if (!Schema::hasColumn($tableName, $column)) {
            $table->boolean($column)->default($default)->index();
        }
    }

    private function addUnsignedInteger(Blueprint $table, string $tableName, string $column, ?int $default = null): void
    {
        if (Schema::hasColumn($tableName, $column)) {
            return;
        }
        $definition = $table->unsignedInteger($column)->nullable();
        if ($default !== null) {
            $definition->default($default);
        }
    }

    private function addUnsignedBigInteger(Blueprint $table, string $tableName, string $column): void
    {
        if (!Schema::hasColumn($tableName, $column)) {
            $table->unsignedBigInteger($column)->nullable()->index();
        }
    }

    private function addDecimal(Blueprint $table, string $tableName, string $column, int $precision, int $scale, float $default): void
    {
        if (!Schema::hasColumn($tableName, $column)) {
            $table->decimal($column, $precision, $scale)->default($default);
        }
    }
};
