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
        Schema::create('apaleo_properties', function (Blueprint $table) {
            $table->id();
            $table->string('apaleo_id')->unique(); // Apaleo property ID
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->nullable();
            
            // Location details
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('state')->nullable();
            
            // Company information
            $table->string('company_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('commercial_register_entry')->nullable();
            
            // Banking details
            $table->string('iban')->nullable();
            $table->string('bic')->nullable();
            $table->string('bank_name')->nullable();
            
            // Settings
            $table->string('timezone')->nullable();
            $table->string('currency_code', 3)->nullable();
            
            // Raw Apaleo data
            $table->json('raw_data')->nullable();
            
            $table->timestamps();
            
            $table->index(['apaleo_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apaleo_properties');
    }
};