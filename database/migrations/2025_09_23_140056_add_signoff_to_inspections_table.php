<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->boolean('vehicle_condition_ok')->default(false)->after('notes');
            $table->text('condition_remark')->nullable()->after('vehicle_condition_ok');
            $table->string('reviewing_driver_signature')->nullable()->after('condition_remark');
            $table->text('driver_remark')->nullable()->after('reviewing_driver_signature');
        });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_condition_ok',
                'condition_remark',
                'reviewing_driver_signature',
                'driver_remark',
            ]);
        });
    }
};
