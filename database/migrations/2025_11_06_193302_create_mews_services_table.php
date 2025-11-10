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
        Schema::create('mews_services', function (Blueprint $table) {
            $table->id();
            $table->string('mews_id')->unique(); // Mews Service ID
            $table->string('enterprise_id'); // Foreign key to mews_enterprises
            $table->string('external_identifier')->nullable();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            
            // Service options
            $table->boolean('bill_as_package')->default(false);
            
            // Service data - discriminated by type
            $table->string('data_discriminator'); // 'Bookable' or 'Additional'
            
            // Bookable service specific fields
            $table->string('start_offset')->nullable(); // Time offset for bookable services
            $table->string('end_offset')->nullable();
            $table->string('occupancy_start_offset')->nullable();
            $table->string('occupancy_end_offset')->nullable();
            $table->string('time_unit_period')->nullable(); // Day, Hour, Month
            
            // Additional service specific fields - promotions
            $table->boolean('promotion_before_checkin')->default(false);
            $table->boolean('promotion_after_checkin')->default(false);
            $table->boolean('promotion_during_stay')->default(false);
            $table->boolean('promotion_before_checkout')->default(false);
            $table->boolean('promotion_after_checkout')->default(false);
            $table->boolean('promotion_during_checkout')->default(false);
            
            // Import tracking
            $table->json('raw_data')->nullable(); // Store raw API response
            $table->timestamp('mews_created_utc')->nullable();
            $table->timestamp('mews_updated_utc')->nullable();
            $table->timestamp('last_imported_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['mews_id']);
            $table->index(['enterprise_id']);
            $table->index(['external_identifier']);
            $table->index(['name']);
            $table->index(['data_discriminator']);
            $table->index(['is_active']);
            
            $table->foreign('enterprise_id')->references('mews_id')->on('mews_enterprises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mews_services');
    }
};
