<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* 1. Guest feedback / customer satisfaction (ISO 9001 §5.2 / §8.2.1) */
        Schema::create('guest_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('feedback_no', 50)->unique();
            $table->foreignId('guest_id')->nullable()->constrained('guests')->nullOnDelete();
            $table->foreignId('stay_id')->nullable()->constrained('stays')->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->unsignedTinyInteger('rating');     /* 1..5 */
            $table->unsignedTinyInteger('cleanliness_score')->nullable();
            $table->unsignedTinyInteger('service_score')->nullable();
            $table->unsignedTinyInteger('value_score')->nullable();
            $table->text('comment')->nullable();
            $table->json('tags')->nullable();          /* ['noise','wifi','breakfast'] */
            $table->enum('status', ['new', 'reviewed', 'addressed', 'closed'])->default('new');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamps();
        });

        /* 2. Formal complaints register (ISO 9001 §8.5.2 corrective action) */
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('complaint_no', 50)->unique();
            $table->foreignId('guest_id')->nullable()->constrained('guests')->nullOnDelete();
            $table->foreignId('stay_id')->nullable()->constrained('stays')->nullOnDelete();
            $table->string('subject', 200);
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'investigating', 'resolved', 'rejected'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resolution')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        /* 3. Document version control (ISO 9001 §4.2.3) */
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->string('versionable_type', 150);
            $table->unsignedBigInteger('versionable_id');
            $table->unsignedInteger('version_number');
            $table->json('snapshot');                  /* full record snapshot at that version */
            $table->text('change_note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['versionable_type', 'versionable_id']);
        });

        /* 4. Risk register (ISO 9001:2015 §6.1 risk-based thinking) */
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('risk_no', 50)->unique();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('category', ['operational', 'financial', 'safety', 'compliance', 'reputational', 'other'])->default('operational');
            $table->unsignedTinyInteger('likelihood');     /* 1..5 */
            $table->unsignedTinyInteger('impact');         /* 1..5 */
            $table->unsignedTinyInteger('risk_score')->nullable(); /* likelihood*impact, computed */
            $table->text('mitigation_plan')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('review_date')->nullable();
            $table->enum('status', ['identified', 'mitigating', 'accepted', 'closed'])->default('identified');
            $table->timestamps();
        });

        /* 5. Supplier scorecards (ISO 9001 §7.4 supplier evaluation) */
        Schema::create('supplier_scorecards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->unsignedTinyInteger('quality_score');      /* 1..5 */
            $table->unsignedTinyInteger('delivery_score');
            $table->unsignedTinyInteger('price_score');
            $table->unsignedTinyInteger('communication_score');
            $table->decimal('overall_score', 4, 2)->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['supplier_id', 'period_start']);
        });

        /* 6. Corrective and Preventive Actions (CAPA) — ISO 9001 §8.5.2 / §8.5.3 */
        Schema::create('corrective_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('capa_no', 50)->unique();
            $table->enum('type', ['corrective', 'preventive']);
            $table->string('source_type', 100)->nullable(); /* e.g. App\Models\Complaint */
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('title', 200);
            $table->text('description');
            $table->text('root_cause')->nullable();
            $table->text('action_taken')->nullable();
            $table->text('verification')->nullable();
            $table->date('target_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->enum('status', ['open', 'in_progress', 'verifying', 'closed', 'cancelled'])->default('open');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corrective_actions');
        Schema::dropIfExists('supplier_scorecards');
        Schema::dropIfExists('risks');
        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('guest_feedbacks');
    }
};
