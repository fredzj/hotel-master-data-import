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
        Schema::create('apaleo_services', function (Blueprint $table) {
            $table->id();
            $table->string('apaleo_id')->unique();
            $table->string('property_id');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->index(['property_id']);
            $table->index(['apaleo_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apaleo_services');
    }
};
