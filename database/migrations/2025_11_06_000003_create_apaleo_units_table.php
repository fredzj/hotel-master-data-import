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
        Schema::create('apaleo_units', function (Blueprint $table) {
            $table->id();
            $table->string('apaleo_id')->unique(); // Apaleo unit ID
            $table->string('property_id'); // Reference to apaleo property
            $table->string('unit_group_id')->nullable(); // Reference to unit group
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('status')->nullable();
            $table->string('condition')->nullable();
            
            // Unit specific fields
            $table->integer('max_persons')->nullable();
            $table->decimal('size', 8, 2)->nullable();
            $table->string('view')->nullable();
            
            // Raw Apaleo data
            $table->json('raw_data')->nullable();
            
            $table->timestamps();
            
            $table->index(['apaleo_id']);
            $table->index(['property_id']);
            $table->index(['unit_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apaleo_units');
    }
};