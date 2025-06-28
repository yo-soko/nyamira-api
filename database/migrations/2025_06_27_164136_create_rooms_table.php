<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Science Lab", "Class 1A"
            $table->string('code')->unique(); // e.g., "LAB1", "CR1"
            $table->integer('capacity')->default(30); // Number of students the room can hold
            $table->enum('type', [
                'classroom',
                'laboratory',
                'library',
                'hall',
                'staff_room',
                'administration',
                'other'
            ])->default('classroom');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
