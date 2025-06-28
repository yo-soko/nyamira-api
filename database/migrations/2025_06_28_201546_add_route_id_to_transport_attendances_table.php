<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transport_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('route_id')->nullable()->after('student_id');

            // Optional foreign key
            // $table->foreign('route_id')->references('id')->on('transport_routes')->onDelete('set null');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transport_attendances', function (Blueprint $table) {
            //
        });
    }
};
