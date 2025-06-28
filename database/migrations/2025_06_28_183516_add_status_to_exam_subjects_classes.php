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
        $table->tinyInteger('status')->default(0); // 0 = pending, 1 = submitted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_subjects_classes', function (Blueprint $table) {
            //
        });
    }
};
