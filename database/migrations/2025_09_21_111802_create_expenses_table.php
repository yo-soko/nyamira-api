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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->string('expense_type'); // e.g. insurance, loan, etc
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('frequency', ['single', 'monthly', 'annual'])->default('single');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->json('photos')->nullable();     // store file paths as JSON
            $table->json('documents')->nullable();  // store file paths as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
