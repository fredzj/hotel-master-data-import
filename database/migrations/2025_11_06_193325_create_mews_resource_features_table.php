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
        Schema::create('mews_resource_features', function (Blueprint $table) {
            $table->id();
            $table->string('mews_id')->unique(); // Mews Resource Feature ID
            $table->string('service_id'); // Foreign key to mews_services
            $table->string('external_identifier')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Classification
            $table->string('classification')->nullable(); // Feature type/category
            
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
            $table->index(['is_active']);
            
            $table->foreign('service_id')->references('mews_id')->on('mews_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mews_resource_features');
    }
};
