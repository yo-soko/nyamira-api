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
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('identity_card_number')->unique()->after('name');
            $table->string('driving_licence_number')->unique()->after('identity_card_number');
            $table->string('personal_number')->nullable()->after('driving_licence_number');
            $table->date('licence_date_issue')->nullable()->after('personal_number');
            $table->date('licence_date_expiry')->nullable()->after('licence_date_issue');

            // File uploads
            $table->string('identity_card_file')->nullable()->after('licence_date_expiry');
            $table->string('driving_licence_file')->nullable()->after('identity_card_file');
            $table->string('passport_photo_file')->nullable()->after('driving_licence_file');

            // Verification
            $table->boolean('verified')->default(false)->after('passport_photo_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'identity_card_number',
                'driving_licence_number',
                'personal_number',
                'licence_date_issue',
                'licence_date_expiry',
                'identity_card_file',
                'driving_licence_file',
                'passport_photo_file',
                'verified',
            ]);
        });
    }
};
