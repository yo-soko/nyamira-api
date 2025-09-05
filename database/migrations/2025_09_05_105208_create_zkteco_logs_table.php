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
        Schema::create('zkteco_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->date('log_date')->nullable();
            $table->timestamp('dropoff_time')->nullable(false);
            $table->dateTime('pickup_time')->nullable();
            $table->dateTime('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zkteco_logs');
    }
};
