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
        Schema::create('mews_companies', function (Blueprint $table) {
            $table->id();
            $table->string('mews_id')->unique();
            $table->string('enterprise_id')->nullable();
            $table->string('chain_id')->nullable();
            $table->string('identifier')->nullable();
            $table->string('name');
            $table->string('mother_company_id')->nullable();
            $table->string('telephone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website_url')->nullable();
            $table->string('invoicing_email')->nullable();
            $table->string('additional_tax_identifier')->nullable();
            $table->string('iata')->nullable();
            $table->string('department')->nullable();
            $table->string('due_interval')->nullable();
            $table->string('reference_identifier')->nullable();
            $table->string('invoice_due_interval')->nullable();
            $table->string('external_identifier')->nullable();
            $table->string('accounting_code')->nullable();
            $table->string('billing_code')->nullable();
            $table->text('notes')->nullable();
            $table->string('tax_identifier')->nullable();
            
            // Address fields
            $table->string('address_id')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country_code')->nullable();
            $table->string('country_subdivision_code')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            // Metadata
            $table->json('raw_data')->nullable();
            $table->timestamp('mews_created_utc')->nullable();
            $table->timestamp('mews_updated_utc')->nullable();
            $table->timestamp('last_imported_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('enterprise_id');
            $table->index('mother_company_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mews_companies');
    }
};
