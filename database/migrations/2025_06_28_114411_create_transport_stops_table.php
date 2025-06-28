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
        Schema::create('transport_stops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->string('stop_name');
            $table->integer('stop_order')->default(0); // for sequencing stops
            $table->time('pickup_time')->nullable();
            $table->time('dropoff_time')->nullable();
            $table->timestamps();

            $table->foreign('route_id')->references('id')->on('transport_routes')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_stops');
    }
};
