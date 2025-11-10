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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->string('external_id')->nullable(); // PMS specific ID
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('max_occupancy')->nullable();
            $table->decimal('size', 8, 2)->nullable(); // in square meters
            $table->timestamps();
            
            $table->unique(['hotel_id', 'external_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
