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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('date')->nullable();

            $table->string('session');
            $table->enum('status', ['Present', 'Absent'])->default('Absent');
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->unique(['student_id','class_id', 'session', 'date']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
