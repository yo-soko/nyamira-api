<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transport_attendances', function (Blueprint $table) {
            $table->id();

            // Foreign key to student_transports.student_id
            $table->unsignedBigInteger('student_id');
            $table->date('date');
            $table->enum('status', ['present', 'absent'])->default('absent');
            $table->timestamp('marked_at')->nullable();

            // Snapshot data for audit
            $table->string('pickup_location')->nullable();
            $table->string('dropoff_location')->nullable();
            $table->time('pickup_time')->nullable();
            $table->time('dropoff_time')->nullable();

            $table->timestamps();

            // Constraints
            $table->foreign('student_id')->references('student_id')->on('student_transports')->onDelete('cascade');
            $table->unique(['student_id', 'date']); // Prevent duplicate attendance
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_attendances');
    }
};
