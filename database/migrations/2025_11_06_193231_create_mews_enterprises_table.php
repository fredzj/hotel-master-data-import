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
        Schema::create('mews_enterprises', function (Blueprint $table) {
            $table->id();
            $table->string('mews_id')->unique(); // Mews Enterprise ID
            $table->string('external_identifier')->nullable();
            $table->string('holding_key')->nullable();
            $table->string('chain_id')->nullable();
            $table->string('chain_name')->nullable();
            $table->string('name');
            $table->string('time_zone_identifier');
            $table->string('legal_environment_code')->nullable();
            $table->string('accommodation_environment_code')->nullable();
            $table->string('accounting_environment_code')->nullable();
            $table->string('tax_environment_code')->nullable();
            $table->string('default_language_code')->nullable();
            $table->string('pricing')->nullable(); // Net/Gross
            $table->integer('tax_precision')->nullable();
            $table->string('website_url')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo_image_id')->nullable();
            $table->string('cover_image_id')->nullable();
            
            // Address fields
            $table->string('address_id')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country_code')->nullable();
            $table->string('country_subdivision_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Subscription info
            $table->string('tax_identifier')->nullable();
            
            // Import tracking
            $table->json('raw_data')->nullable(); // Store raw API response
            $table->timestamp('linked_utc')->nullable();
            $table->timestamp('mews_created_utc')->nullable();
            $table->timestamp('mews_updated_utc')->nullable();
            $table->timestamp('last_imported_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['mews_id']);
            $table->index(['external_identifier']);
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mews_enterprises');
    }
};
