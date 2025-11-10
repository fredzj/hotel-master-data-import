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
        Schema::create('mews_resources', function (Blueprint $table) {
            $table->id();
            $table->string('mews_id')->unique(); // Mews Resource ID
            $table->string('enterprise_id'); // Foreign key to mews_enterprises
            $table->string('parent_resource_id')->nullable(); // Self-referencing for nested resources
            $table->string('name');
            $table->boolean('is_active')->default(true);
            
            // Resource state
            $table->string('state')->nullable(); // Dirty, Clean, Inspected, OutOfService, OutOfOrder
            $table->string('state_reason')->nullable();
            
            // Descriptions in multiple languages (JSON)
            $table->json('descriptions')->nullable();
            $table->json('external_names')->nullable();
            $table->json('directions')->nullable();
            
            // Resource data - discriminated by type
            $table->string('data_discriminator'); // 'Space', 'Object', 'Person'
            
            // Space-specific data
            $table->string('floor_number')->nullable();
            $table->text('location_notes')->nullable();
            
            // Import tracking
            $table->json('raw_data')->nullable(); // Store raw API response
            $table->timestamp('mews_created_utc')->nullable();
            $table->timestamp('mews_updated_utc')->nullable();
            $table->timestamp('last_imported_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['mews_id']);
            $table->index(['enterprise_id']);
            $table->index(['parent_resource_id']);
            $table->index(['name']);
            $table->index(['state']);
            $table->index(['data_discriminator']);
            $table->index(['is_active']);
            
            $table->foreign('enterprise_id')->references('mews_id')->on('mews_enterprises')->onDelete('cascade');
            $table->foreign('parent_resource_id')->references('mews_id')->on('mews_resources')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mews_resources');
    }
};
