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
        Schema::create('charging_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->string('vendor_id')->nullable();
            $table->bigInteger('odometer')->nullable();

            $table->dateTime('charging_started');
            $table->dateTime('charging_ended')->nullable();
            $table->integer('charging_duration')->nullable(); // in minutes

            $table->decimal('total_energy', 10, 2)->nullable(); // kWh
            $table->decimal('energy_price', 10, 2)->nullable(); // price per kWh
            $table->decimal('energy_cost', 12, 2)->nullable();  // computed total

            $table->string('reference')->nullable();
            $table->boolean('is_personal')->default(false);

            $table->json('photos')->nullable();
            $table->json('documents')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charging_histories');
    }
};
