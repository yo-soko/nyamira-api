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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('shift_name');
            $table->string('start_time');
            $table->string('end_time');
            $table->json('days')->nullable();
            $table->string('day_off')->nullable();
            $table->boolean('recurring')->default(false);
            $table->boolean('status')->default(true);
            $table->string('morning_from')->nullable();
            $table->string('morning_to')->nullable();
            $table->string('lunch_from')->nullable();
            $table->string('lunch_to')->nullable();
            $table->string('evening_from')->nullable();
            $table->string('evening_to')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
