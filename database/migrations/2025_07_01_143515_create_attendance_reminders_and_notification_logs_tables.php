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
        Schema::create('attendance_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users');
            $table->enum('session', ['morning', 'afternoon']);
            $table->boolean('is_first_reminder')->default(false);
            $table->timestamp('sent_at');
            $table->timestamp('marked_as_read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // defaults to `users` table
            $table->string('type');
            $table->string('session_type');
            $table->timestamp('sent_at');
            $table->string('delivery_method');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('attendance_reminders');
    }
};
