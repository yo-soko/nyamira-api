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
        Schema::create('work_tickets', function (Blueprint $table) {
            $table->id();
            // Driver & Vehicle
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');

            // Authorization
            $table->string('authorized_by')->nullable(); // officer name
            $table->string('authorized_designation')->nullable();
            $table->string('authorized_signature')->nullable(); // path to signature image

            // Journey Details
            $table->date('travel_date');
            $table->string('start_point');
            $table->string('end_point');
            $table->text('purpose')->nullable();

            // Passengers
            $table->json('passengers')->nullable(); // store as ["John Doe", "Jane Smith"]

            // Fuel Details
            $table->decimal('fuel_used', 10, 2)->nullable();
            $table->string('fuel_source')->nullable(); // e.g. "Company Pump", "Shell Station"

            // Logs/Notes
            $table->integer('start_mileage')->nullable();
            $table->integer('end_mileage')->nullable();
            $table->text('notes')->nullable();

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_tickets');
    }
};
