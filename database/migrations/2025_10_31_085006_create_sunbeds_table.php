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
        Schema::create('transformed_sunbeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sunbed_area_id')->constrained('transformed_sunbed_areas')->onDelete('cascade');
            $table->foreignId('sunbed_type_id')->constrained('transformed_sunbed_types')->onDelete('cascade');
            $table->string('name');
            $table->string('identifier')->unique();
            $table->enum('status', ['available', 'occupied', 'maintenance', 'out_of_order'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transformed_sunbeds');
    }
};
