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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade'); // Asset/Vehicle
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Low');
            $table->dateTime('reported_at');
            $table->string('summary');
            $table->text('description')->nullable();
            $table->string('labels')->nullable(); // e.g. Electrical, Mechanical
            $table->unsignedBigInteger('reported_by'); // user who reported
            $table->unsignedBigInteger('assigned_to')->nullable(); // user assigned
            $table->dateTime('due_date')->nullable();
            $table->integer('primary_meter_due')->nullable(); // optional threshold
            $table->string('status')->default('Open'); // Open, In Progress, Resolved, Closed
            $table->timestamps();

            $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
