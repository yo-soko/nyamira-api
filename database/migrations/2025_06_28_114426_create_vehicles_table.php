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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('model')->nullable();
            $table->integer('capacity')->default(0);
            $table->unsignedBigInteger('route_id')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->timestamps();

            $table->foreign('route_id')->references('id')->on('transport_routes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
