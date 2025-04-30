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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('profile_photo')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('contact_number');
            $table->string('emp_code')->unique();
            $table->date('dob');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('nationality');
            $table->date('joining_date');
            $table->unsignedBigInteger('shift_id');
            $table->string('department');
            $table->string('designation');
            $table->string('blood_group')->nullable();
            $table->boolean('status')->default(1);
            $table->string('about', 60)->nullable();
    
            // Address
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('zipcode')->nullable();
    
            // Emergency Contacts
            $table->string('emergency_contact1')->nullable();
            $table->string('emergency_relation1')->nullable();
            $table->string('emergency_name1')->nullable();
    
            $table->string('emergency_contact2')->nullable();
            $table->string('emergency_relation2')->nullable();
            $table->string('emergency_name2')->nullable();
    
            // Bank Info
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('branch')->nullable();
    
            // Auth
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
