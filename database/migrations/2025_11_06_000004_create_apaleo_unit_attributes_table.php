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
        Schema::create('apaleo_unit_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('unit_id'); // Reference to apaleo unit
            $table->string('name');
            $table->text('value')->nullable();
            $table->string('type')->nullable();
            $table->string('unit_of_measure')->nullable();
            
            // Raw Apaleo data
            $table->json('raw_data')->nullable();
            
            $table->timestamps();
            
            $table->index(['unit_id']);
            $table->index(['name']);
            $table->unique(['unit_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apaleo_unit_attributes');
    }
};