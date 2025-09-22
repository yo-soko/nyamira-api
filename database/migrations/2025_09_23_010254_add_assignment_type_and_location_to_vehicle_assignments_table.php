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
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->string('assignment_type')->nullable()->after('operator_id');
            $table->string('assignment_location')->nullable()->after('assignment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_assignments', function (Blueprint $table) {
            $table->dropColumn(['assignment_type', 'assignment_location']);
        });
    }
};
