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
        Schema::create('exam_subjects_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('term_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('school_class_id');
            $table->timestamps();
            $table->tinyInteger('status')->default(0);

            $table->unique(['exam_id', 'subject_id', 'level_id', 'term_id', 'school_class_id'], 'unique_exam_subject_class');

            // optional: add foreign keys if you want strict constraints
            // $table->foreign('school_class_id')->references('id')->on('school_classes')->onDelete('cascade');
            // $table->foreign('level_id')->references('id')->on('class_levels')->onDelete('cascade');
            // $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_subjects_classes');
    }
};
