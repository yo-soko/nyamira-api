<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transport_attendances', function (Blueprint $table) {
            $table->id();

            // Foreign keys and relationships
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('route_id')->nullable();

            $table->date('date');

            // Attendance status per session
            $table->enum('pickup_status', ['present', 'absent'])->nullable();
            $table->enum('dropoff_status', ['present', 'absent'])->nullable();

            // Snapshot data for audit
            $table->string('pickup_location')->nullable();
            $table->string('dropoff_location')->nullable();
            $table->time('pickup_time')->nullable();
            $table->time('dropoff_time')->nullable();
           
            $table->timestamps();

      
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_attendances');
    }
};
