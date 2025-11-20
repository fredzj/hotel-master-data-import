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
        Schema::create('transformed_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('transformed_room_types')->onDelete('cascade');
            $table->foreignId('floor_id')->nullable()->constrained('transformed_floors')->onDelete('set null');
            $table->string('external_id')->nullable(); // PMS specific ID
            $table->string('name');
            $table->string('number')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'occupied', 'maintenance', 'out_of_order'])->default('available');
            $table->timestamps();
            
            $table->unique(['room_type_id', 'external_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transformed_rooms');
    }
};
