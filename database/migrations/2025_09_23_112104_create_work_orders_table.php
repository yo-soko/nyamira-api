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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Voided'])->default('Pending');
            $table->enum('priority_class', ['Scheduled', 'Non-Scheduled', 'Emergency'])->default('Scheduled');
            $table->dateTime('issue_date');
            $table->foreignId('issued_by')->constrained('users')->cascadeOnDelete();
            $table->dateTime('scheduled_start_date')->nullable();
            $table->dateTime('actual_start_date')->nullable();
            $table->dateTime('expected_completion_date')->nullable();
            $table->dateTime('actual_completion_date')->nullable();
            $table->integer('start_odometer')->nullable();
            $table->integer('completion_odometer')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('labels')->nullable();
            $table->foreignId('vendor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('invoice_number')->nullable();
            $table->string('po_number')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('parts_cost', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
