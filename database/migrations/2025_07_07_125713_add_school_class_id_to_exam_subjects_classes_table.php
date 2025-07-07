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
        Schema::table('exam_subjects_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('school_class_id')->after('level_id');
        });
    }

    public function down(): void
    {
        Schema::table('exam_subjects_classes', function (Blueprint $table) {
            $table->dropColumn('school_class_id');
        });
    }

};
