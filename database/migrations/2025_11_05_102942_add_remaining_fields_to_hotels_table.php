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
        Schema::table('transformed_hotels', function (Blueprint $table) {
            // Property details
            $table->string('code')->nullable()->after('external_id');
            $table->boolean('is_template')->default(false)->after('code');
            $table->string('company_name')->nullable()->after('description');
            $table->text('commercial_register_entry')->nullable()->after('company_name');
            $table->string('tax_id')->nullable()->after('commercial_register_entry');
            
            // Banking information
            $table->string('bank_iban')->nullable()->after('currency');
            $table->string('bank_bic')->nullable()->after('bank_iban');
            $table->string('bank_name')->nullable()->after('bank_bic');
            
            // Payment terms (storing as JSON for multilingual support)
            $table->json('payment_terms')->nullable()->after('bank_name');
            
            // System fields
            $table->string('status')->nullable()->after('payment_terms');
            $table->boolean('is_archived')->default(false)->after('status');
            $table->timestamp('external_created_at')->nullable()->after('is_archived');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transformed_hotels', function (Blueprint $table) {
            $table->dropColumn([
                'code', 'is_template', 'company_name', 'commercial_register_entry', 
                'tax_id', 'bank_iban', 'bank_bic', 'bank_name', 'payment_terms',
                'status', 'is_archived', 'external_created_at'
            ]);
        });
    }
};
