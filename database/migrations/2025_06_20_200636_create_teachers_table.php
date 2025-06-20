<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name', 100);
            $table->date('date_of_birth');
            $table->string('email', 100)->unique();
            $table->string('phone', 100);
            $table->string('id_no');
            $table->text('address');
            $table->string('education_level');
            $table->string('years_of_experience', 100);
            $table->string('gender', 50);
            $table->unsignedBigInteger('department');
            $table->tinyInteger('status')->default(1); // 1 = active
            $table->timestamp('date_created')->useCurrent();
            $table->timestamp('date_updated')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void {
        Schema::dropIfExists('teachers');
    }
};
