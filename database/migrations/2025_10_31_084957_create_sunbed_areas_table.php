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
        Schema::create('transformed_sunbed_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('transformed_hotels')->onDelete('cascade');
            $table->string('name');
            $table->text('location_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transformed_sunbed_areas');
    }
};
