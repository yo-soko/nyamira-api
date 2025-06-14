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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('student_id'); // FK to students table

            $table->string('guardian_first_name');
            $table->string('guardian_last_name');
            $table->string('guardian_relationship', 100);

            $table->string('first_phone', 15)->nullable();
            $table->string('second_phone', 15);
            $table->text('address')->nullable();
            $table->string('id_number', 50)->nullable();
            $table->string('email')->unique();
            $table->text('guardian_about')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
