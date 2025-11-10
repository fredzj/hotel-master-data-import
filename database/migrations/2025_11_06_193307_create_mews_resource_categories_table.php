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
        Schema::create('mews_resource_categories', function (Blueprint $table) {
            $table->id();
            $table->string('mews_id')->unique(); // Mews Resource Category ID
            $table->string('service_id'); // Foreign key to mews_services
            $table->string('external_identifier')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Resource category type
            $table->string('type'); // Room, Bed, Dorm, Apartment, Suite, Villa, etc.
            
            // Capacity and specifications
            $table->integer('normal_bed_count')->nullable();
            $table->integer('extra_bed_count')->nullable();
            $table->integer('included_persons')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('ordering')->nullable();
            
            // Dimensions
            $table->decimal('area', 8, 2)->nullable(); // Room area in square meters
            
            // Import tracking
            $table->json('raw_data')->nullable(); // Store raw API response
            $table->timestamp('mews_created_utc')->nullable();
            $table->timestamp('mews_updated_utc')->nullable();
            $table->timestamp('last_imported_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['mews_id']);
            $table->index(['service_id']);
            $table->index(['external_identifier']);
            $table->index(['name']);
            $table->index(['type']);
            $table->index(['is_active']);
            
            $table->foreign('service_id')->references('mews_id')->on('mews_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mews_resource_categories');
    }
};
