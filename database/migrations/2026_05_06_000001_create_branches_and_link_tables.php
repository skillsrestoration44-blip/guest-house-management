<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Multi-branch support migration.
     *
     * Creates the `branches` table and adds nullable `branch_id` foreign keys
     * to every operational table that should be scoped per branch.
     */
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 150);
            $table->string('phone', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('address')->nullable();
            $table->string('manager_name', 150)->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        $tablesNeedingBranch = [
            'staff', 'users', 'rooms', 'room_types', 'facilities', 'guests',
            'bookings', 'stays', 'invoices', 'payments', 'services',
            'housekeeping_tasks', 'maintenance_requests', 'suppliers',
            'stock_items', 'stock_movements', 'expenses', 'salaries',
            'online_booking_requests', 'audit_logs', 'login_histories',
            'service_charges', 'refunds', 'receipts',
        ];

        foreach ($tablesNeedingBranch as $tableName) {
            if (! Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $t) {
                    $t->foreignId('branch_id')->nullable()->after('id')
                        ->constrained('branches')->cascadeOnUpdate()->nullOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'staff', 'users', 'rooms', 'room_types', 'facilities', 'guests',
            'bookings', 'stays', 'invoices', 'payments', 'services',
            'housekeeping_tasks', 'maintenance_requests', 'suppliers',
            'stock_items', 'stock_movements', 'expenses', 'salaries',
            'online_booking_requests', 'audit_logs', 'login_histories',
            'service_charges', 'refunds', 'receipts',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $t) {
                    $t->dropConstrainedForeignId('branch_id');
                });
            }
        }

        Schema::dropIfExists('branches');
    }
};
