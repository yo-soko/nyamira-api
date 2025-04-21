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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('payment_date')->nullable();
            $table->decimal('basic_salary', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('reference_code')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['paid', 'unpaid'])->default('paid');
    
            // Allowances
            $table->decimal('allowance1', 10, 2)->nullable();
            $table->decimal('allowance2', 10, 2)->nullable();
            $table->decimal('allowance3', 10, 2)->nullable();
            $table->decimal('bonus', 10, 2)->nullable();
            $table->decimal('others1', 10, 2)->nullable();
    
            // Deductions
            $table->decimal('deduction1', 10, 2)->nullable();
            $table->decimal('deduction2', 10, 2)->nullable();
            $table->decimal('deduction3', 10, 2)->nullable();
            $table->decimal('deduction4', 10, 2)->nullable();
            $table->decimal('others', 10, 2)->nullable();
    
            // Totals
            $table->decimal('total_deduction', 10, 2)->nullable();
            $table->decimal('net_salary', 10, 2)->nullable();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};
