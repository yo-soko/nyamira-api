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
        Schema::create('attendancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->date('date');
            $table->dateTime('clock_in')->nullable();
            $table->dateTime('clock_out')->nullable();
            $table->string('status')->default('present'); // present, absent, late, holiday
            $table->string('production')->nullable(); // e.g. 9h 00m
            $table->dateTime('break_start')->nullable(); // e.g. 1h 13m
            $table->dateTime('break_end')->nullable(); // e.g. 1h 13m
            $table->string('overtime')->nullable(); // e.g. 00h 50m
            $table->time('total_hours')->nullable(); // e.g. 09h 50m
            $table->json('progress')->nullable(); // could store percentage values
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendancies');
    }
};
