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
        Schema::create('period_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('lesson_duration'); // in minutes
            $table->time('start_time');
            $table->integer('break_after_lessons');
            $table->integer('break_duration');
            $table->integer('lunch_after_lessons');
            $table->integer('lunch_duration');
            $table->unSignedBigInteger('user_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_rules');
    }
};
