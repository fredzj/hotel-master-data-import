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
        Schema::create('apaleo_unit_groups', function (Blueprint $table) {
            $table->id();
            $table->string('apaleo_id')->unique(); // Apaleo unit group ID
            $table->string('property_id'); // Reference to apaleo property
            $table->string('code')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('max_persons')->nullable();
            $table->integer('member_count')->nullable();
            
            // Raw Apaleo data
            $table->json('raw_data')->nullable();
            
            $table->timestamps();
            
            $table->index(['apaleo_id']);
            $table->index(['property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apaleo_unit_groups');
    }
};