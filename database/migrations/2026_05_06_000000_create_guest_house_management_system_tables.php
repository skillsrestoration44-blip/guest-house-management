<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Guest House Management System - All in One Migration
     * Laravel 12 / MySQL 8.x / utf8mb4
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Core System: Staff, Users, Roles, Permissions
        |--------------------------------------------------------------------------
        */
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('staff_code', 50)->unique();
            $table->string('full_name', 150);
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('phone', 50)->nullable()->index();
            $table->string('email', 150)->nullable()->index();
            $table->text('address')->nullable();
            $table->string('position', 100)->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->date('hire_date')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'resigned', 'suspended'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->cascadeOnUpdate()->nullOnDelete();
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('username', 100)->unique();
            $table->string('phone', 50)->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active')->index();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('display_name', 150);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module', 100)->index();
            $table->string('name', 150)->unique();
            $table->string('display_name', 150);
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['user_id', 'role_id']);
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['permission_id', 'role_id']);
        });

        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('attendance_date')->index();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'leave'])->default('present')->index();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
            $table->unique(['staff_id', 'attendance_date']);
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Room Management
        |--------------------------------------------------------------------------
        */
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('default_price_per_night', 12, 2)->default(0);
            $table->decimal('default_price_per_hour', 12, 2)->nullable();
            $table->unsignedInteger('max_guests')->default(1);
            $table->unsignedInteger('bed_count')->default(1);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('room_number', 50)->unique();
            $table->string('floor', 50)->nullable();
            $table->unsignedInteger('bed_count')->default(1);
            $table->unsignedInteger('max_guests')->default(1);
            $table->decimal('price_per_night', 12, 2)->default(0);
            $table->decimal('price_per_hour', 12, 2)->nullable();
            $table->enum('status', ['available', 'booked', 'occupied', 'cleaning', 'maintenance', 'blocked'])->default('available')->index();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('facility_room', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->enum('item_condition', ['good', 'damaged', 'missing'])->nullable();
            $table->text('note')->nullable();
            $table->unique(['room_id', 'facility_id']);
        });

        Schema::create('room_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Guest Management
        |--------------------------------------------------------------------------
        */
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('guest_code', 50)->unique();
            $table->string('full_name', 150);
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('phone', 50)->nullable()->index();
            $table->string('email', 150)->nullable()->index();
            $table->string('nationality', 100)->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_blacklisted')->default(false)->index();
            $table->text('blacklist_reason')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('guest_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('document_type', ['id_card', 'passport', 'visa', 'photo', 'other'])->index();
            $table->string('document_number', 100)->nullable()->index();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_path');
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Booking / Reservation
        |--------------------------------------------------------------------------
        */
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no', 50)->unique();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('booking_source', ['walk_in', 'phone', 'website', 'facebook', 'agency'])->default('walk_in');
            $table->date('check_in_date')->index();
            $table->date('check_out_date')->index();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->unsignedInteger('adults')->default(1);
            $table->unsignedInteger('children')->default(0);
            $table->unsignedInteger('total_guests')->default(1);
            $table->decimal('room_price', 12, 2)->default(0);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'no_show'])->default('pending')->index();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('booking_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->unique(['booking_id', 'guest_id']);
        });

        Schema::create('booking_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->text('reason')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('created_at')->nullable();
        });

        /*
        |--------------------------------------------------------------------------
        | 5. Check-in / Check-out / Stay
        |--------------------------------------------------------------------------
        */
        Schema::create('stays', function (Blueprint $table) {
            $table->id();
            $table->string('stay_no', 50)->unique();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('actual_check_in_at')->index();
            $table->dateTime('expected_check_out_at')->index();
            $table->dateTime('actual_check_out_at')->nullable()->index();
            $table->foreignId('check_in_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('check_out_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('room_price', 12, 2)->default(0);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->decimal('damage_fee', 12, 2)->default(0);
            $table->decimal('late_checkout_fee', 12, 2)->default(0);
            $table->enum('status', ['checked_in', 'checked_out', 'transferred', 'cancelled'])->default('checked_in')->index();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('stay_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stay_id')->constrained('stays')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->unique(['stay_id', 'guest_id']);
        });

        Schema::create('room_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stay_id')->constrained('stays')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('from_room_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('to_room_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('transfer_at')->index();
            $table->decimal('price_difference', 12, 2)->default(0);
            $table->text('reason')->nullable();
            $table->foreignId('transferred_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 6. Payment & Billing
        |--------------------------------------------------------------------------
        */
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 50)->unique();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('stay_id')->nullable()->constrained('stays')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('invoice_date')->index();
            $table->date('due_date')->nullable()->index();
            $table->decimal('room_total', 12, 2)->default(0);
            $table->decimal('service_total', 12, 2)->default(0);
            $table->decimal('damage_total', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);
            $table->enum('status', ['draft', 'unpaid', 'partial', 'paid', 'cancelled', 'refunded'])->default('draft')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('item_type', ['room', 'service', 'damage', 'discount', 'tax', 'other'])->index();
            $table->unsignedBigInteger('reference_id')->nullable()->index();
            $table->string('description');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no', 50)->unique();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('stay_id')->nullable()->constrained('stays')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('payment_date')->index();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('payment_type', ['deposit', 'room_fee', 'service_fee', 'full', 'partial', 'refund'])->index();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('reference_no', 150)->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled', 'refunded'])->default('completed')->index();
            $table->text('note')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no', 50)->unique();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->cascadeOnUpdate()->nullOnDelete();
            $table->dateTime('issued_at')->index();
            $table->foreignId('issued_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refund_no', 50)->unique();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->text('reason')->nullable();
            $table->dateTime('refunded_at')->index();
            $table->foreignId('refunded_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->index();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 7. Service Management
        |--------------------------------------------------------------------------
        */
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('category', 100)->nullable()->index();
            $table->string('unit', 50)->default('unit');
            $table->decimal('price', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
        });

        Schema::create('service_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stay_id')->nullable()->constrained('stays')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('charge_date')->index();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('status', ['pending', 'billed', 'cancelled'])->default('pending')->index();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 8. Housekeeping Management
        |--------------------------------------------------------------------------
        */
        Schema::create('housekeeping_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_no', 50)->unique();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('staff')->cascadeOnUpdate()->nullOnDelete();
            $table->dateTime('scheduled_at')->index();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->enum('status', ['pending', 'cleaning', 'completed', 'cancelled'])->default('pending')->index();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('housekeeping_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('housekeeping_task_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('housekeeping_task_id')->constrained('housekeeping_tasks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('checklist_item_id')->constrained('housekeeping_checklist_items')->cascadeOnUpdate()->restrictOnDelete();
            $table->boolean('is_checked')->default(false);
            $table->text('note')->nullable();
            $table->string('photo_path')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->dateTime('checked_at')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 9. Maintenance & Repair Management
        |--------------------------------------------------------------------------
        */
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_no', 50)->unique();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('staff')->cascadeOnUpdate()->nullOnDelete();
            $table->string('issue_type', 150);
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->index();
            $table->enum('status', ['pending', 'in_progress', 'waiting_material', 'completed', 'cancelled'])->default('pending')->index();
            $table->dateTime('reported_at')->index();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('maintenance_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_request_id')->constrained('maintenance_requests')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('photo_path');
            $table->enum('type', ['before', 'after', 'other'])->default('other');
            $table->timestamps();
        });

        Schema::create('maintenance_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_request_id')->constrained('maintenance_requests')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('cost_type', ['material', 'labor', 'transport', 'other'])->default('other');
            $table->string('description');
            $table->decimal('amount', 12, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 10. Inventory & Stock Management
        |--------------------------------------------------------------------------
        */
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('phone', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('address')->nullable();
            $table->string('contact_person', 150)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('stock_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_category_id')->constrained('stock_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->cascadeOnUpdate()->nullOnDelete();
            $table->string('name', 150);
            $table->string('sku', 100)->nullable()->unique();
            $table->string('unit', 50)->default('pcs');
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->nullable();
            $table->decimal('current_stock', 12, 2)->default(0)->index();
            $table->decimal('minimum_stock', 12, 2)->default(0);
            $table->date('expiry_date')->nullable()->index();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('movement_type', ['in', 'out', 'adjustment', 'damaged', 'expired'])->index();
            $table->string('reference_type', 100)->nullable()->index();
            $table->unsignedBigInteger('reference_id')->nullable()->index();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->text('note')->nullable();
            $table->dateTime('movement_at')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 11. Accounting & Finance
        |--------------------------------------------------------------------------
        */
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_no', 50)->unique();
            $table->foreignId('expense_category_id')->constrained('expense_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('expense_date')->index();
            $table->text('description');
            $table->decimal('amount', 12, 2)->default(0);
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->cascadeOnUpdate()->nullOnDelete();
            $table->string('reference_no', 150)->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('salary_month', 7)->index(); // Format: YYYY-MM
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('deduction', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->dateTime('paid_at')->nullable();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->cascadeOnUpdate()->nullOnDelete();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
            $table->unique(['staff_id', 'salary_month']);
        });

        /*
        |--------------------------------------------------------------------------
        | 12. Notification Management
        |--------------------------------------------------------------------------
        */
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('title', 150);
            $table->text('message');
            $table->enum('type', ['system', 'booking', 'payment', 'debt', 'housekeeping', 'maintenance', 'stock'])->default('system')->index();
            $table->enum('channel', ['system', 'sms', 'email', 'telegram', 'whatsapp'])->default('system')->index();
            $table->string('reference_type', 100)->nullable()->index();
            $table->unsignedBigInteger('reference_id')->nullable()->index();
            $table->boolean('is_read')->default(false)->index();
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('title', 150);
            $table->text('message');
            $table->enum('channel', ['system', 'sms', 'email', 'telegram', 'whatsapp'])->default('system');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 13. Website / Online Booking
        |--------------------------------------------------------------------------
        */
        Schema::create('website_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 150)->unique();
            $table->string('title', 150);
            $table->longText('content')->nullable();
            $table->string('meta_title', 150)->nullable();
            $table->text('meta_description')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('online_booking_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_no', 50)->unique();
            $table->string('guest_name', 150);
            $table->string('phone', 50);
            $table->string('email', 150)->nullable();
            $table->foreignId('room_type_id')->nullable()->constrained('room_types')->cascadeOnUpdate()->nullOnDelete();
            $table->date('check_in_date')->index();
            $table->date('check_out_date')->index();
            $table->unsignedInteger('total_guests')->default(1);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->cascadeOnUpdate()->nullOnDelete();
            $table->string('payment_reference', 150)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending')->index();
            $table->foreignId('approved_booking_id')->nullable()->constrained('bookings')->cascadeOnUpdate()->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 14. Security & Audit Log
        |--------------------------------------------------------------------------
        */
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('ip_address', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->dateTime('login_at')->index();
            $table->dateTime('logout_at')->nullable();
            $table->enum('status', ['success', 'failed'])->default('success')->index();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('action', 100)->index();
            $table->string('module', 100)->index();
            $table->string('auditable_type', 150)->nullable()->index();
            $table->unsignedBigInteger('auditable_id')->nullable()->index();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        /*
        |--------------------------------------------------------------------------
        | 15. System Settings
        |--------------------------------------------------------------------------
        */
        Schema::create('guest_house_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website', 150)->nullable();
            $table->string('tax_number', 100)->nullable();
            $table->string('stamp_image')->nullable();
            $table->string('signature_image')->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('timezone', 100)->default('Asia/Phnom_Penh');
            $table->timestamps();
        });

        Schema::create('code_settings', function (Blueprint $table) {
            $table->id();
            $table->string('code_type', 100)->unique();
            $table->string('prefix', 20);
            $table->unsignedInteger('next_number')->default(1);
            $table->unsignedInteger('digit_length')->default(5);
            $table->string('example', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 150)->unique();
            $table->longText('value')->nullable();
            $table->string('type', 50)->default('string');
            $table->string('setting_group', 100)->nullable()->index();
            $table->timestamps();
        });

        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->enum('backup_type', ['manual', 'auto'])->default('manual')->index();
            $table->enum('status', ['success', 'failed'])->default('success')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('code_settings');
        Schema::dropIfExists('guest_house_settings');

        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('login_histories');

        Schema::dropIfExists('online_booking_requests');
        Schema::dropIfExists('website_pages');

        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('notifications');

        Schema::dropIfExists('salaries');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');

        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_items');
        Schema::dropIfExists('stock_categories');
        Schema::dropIfExists('suppliers');

        Schema::dropIfExists('maintenance_costs');
        Schema::dropIfExists('maintenance_photos');
        Schema::dropIfExists('maintenance_requests');

        Schema::dropIfExists('housekeeping_task_checks');
        Schema::dropIfExists('housekeeping_checklist_items');
        Schema::dropIfExists('housekeeping_tasks');

        Schema::dropIfExists('service_charges');
        Schema::dropIfExists('services');

        Schema::dropIfExists('refunds');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payment_methods');

        Schema::dropIfExists('room_transfers');
        Schema::dropIfExists('stay_guests');
        Schema::dropIfExists('stays');

        Schema::dropIfExists('booking_status_histories');
        Schema::dropIfExists('booking_guests');
        Schema::dropIfExists('bookings');

        Schema::dropIfExists('guest_documents');
        Schema::dropIfExists('guests');

        Schema::dropIfExists('room_images');
        Schema::dropIfExists('facility_room');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_types');

        Schema::dropIfExists('staff_attendances');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('staff');
    }
};
