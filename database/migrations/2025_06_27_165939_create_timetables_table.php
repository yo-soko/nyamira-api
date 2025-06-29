<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_level_id')->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('teacher_id')->constrained();
            $table->foreignId('room_id')->constrained();
            $table->foreignId('time_slot_id')->constrained();
            $table->tinyInteger('day_of_week'); // 1-5 for Monday-Friday
            $table->timestamps();

            $table->unique(['class_level_id', 'day_of_week', 'time_slot_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
