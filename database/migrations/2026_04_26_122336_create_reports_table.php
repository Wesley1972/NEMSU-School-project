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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // Foreign Key to link to the User
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('photo_path')->nullable(); // Saves the file location of the image
            $table->string('location');
            $table->string('incident_type');
            $table->boolean('is_hazardous')->default(false); // Checkbox turns into a true/false
            $table->text('notes')->nullable();
            $table->string('status')->default('Pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
