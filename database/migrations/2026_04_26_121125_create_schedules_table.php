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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            // This is the Foreign Key connecting it to the users table!
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('waste_type');
            $table->integer('weight')->nullable(); // nullable because it's estimated
            $table->string('pickup_address');
            $table->string('location_type');
            $table->string('status')->default('Pending'); // Helpful for later!
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
