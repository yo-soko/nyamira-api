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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Identification
            $table->string('name'); // nickname
            $table->string('vin')->nullable(); // Vehicle Identification Number
            $table->string('license_plate')->unique();
            $table->string('type')->nullable(); // car, truck, bus, etc.
            $table->string('fuel_type')->nullable(); // petrol, diesel, electric, hybrid
            $table->year('year')->nullable();
            $table->string('make')->nullable(); // Toyota, Nissan, etc.
            $table->string('model')->nullable(); // Hilux, Prado, etc.
            $table->string('trim')->nullable(); // XLE, GX, etc.

            // Registration & classification
            $table->string('registration_state')->nullable();
            $table->enum('status', ['active', 'inactive', 'sold', 'scrapped'])->default('active');
            $table->string('group')->nullable(); // Department, Branch, Project
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete(); // assigned department
            $table->enum('ownership', ['owned', 'leased', 'hired'])->default('owned');

            // Specifications
            $table->string('color')->nullable();
            $table->string('body_type')->nullable(); // sedan, pickup, van, etc.
            $table->string('body_subtype')->nullable(); // extended cab, crew cab, etc.
            $table->decimal('msrp', 12, 2)->nullable(); // vehicle cost value
            $table->string('photo')->nullable(); // path to uploaded photo
            $table->json('labels')->nullable(); // tags/labels (JSON array)

            // Lifecycle
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->date('retirement_date')->nullable();

            // Financial
            $table->string('insurance_policy_number')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('loan_details')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
