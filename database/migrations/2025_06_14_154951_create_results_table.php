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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('term_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('class_id');
            $table->integer('marks')->nullable();
            $table->integer('absent')->nullable();
            $table->string('comments')->nullable();
            $table->string('grade')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
