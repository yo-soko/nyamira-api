<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Parent inspection record
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inspector_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('inspection_date');
            $table->integer('odometer_reading')->nullable();
            $table->boolean('is_void')->default(false);
            $table->enum('status', ['Pass', 'Fail', 'Pending'])->default('Pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Checklist items for each inspection
        Schema::create('inspection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->cascadeOnDelete();
            $table->string('item_name'); // e.g. "Engine"
            $table->enum('status', ['Pass', 'Fail', 'N/A'])->default('N/A');
            $table->text('remark')->nullable();
            $table->string('attachment')->nullable(); // file path (photo, signature, etc.)
            $table->timestamps();
        });

        // (Optional) separate media table if you want many files per item
        Schema::create('inspection_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_item_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('type')->nullable(); // photo, signature, doc
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_media');
        Schema::dropIfExists('inspection_items');
        Schema::dropIfExists('inspections');
    }
};
