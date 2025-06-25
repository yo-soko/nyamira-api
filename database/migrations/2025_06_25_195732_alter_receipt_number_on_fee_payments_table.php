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
        Schema::table('fee_payments', function (Blueprint $table) {
            // Do NOT add ->unique() again — index already exists
            $table->string('receipt_number')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->string('receipt_number')->nullable(false)->change(); // optional
        });
    }
};
