<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MewsCompany extends Model
{
    protected $table = 'mews_companies';

    protected $fillable = [
        'mews_id',
        'enterprise_id',
        'chain_id',
        'identifier',
        'name',
        'mother_company_id',
        'telephone',
        'contact_email',
        'website_url',
        'invoicing_email',
        'additional_tax_identifier',
        'iata',
        'department',
        'due_interval',
        'reference_identifier',
        'invoice_due_interval',
        'external_identifier',
        'accounting_code',
        'billing_code',
        'notes',
        'tax_identifier',
        'address_id',
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'country_code',
        'country_subdivision_code',
        'latitude',
        'longitude',
        'raw_data',
        'mews_created_utc',
        'mews_updated_utc',
        'last_imported_at',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'mews_created_utc' => 'datetime',
        'mews_updated_utc' => 'datetime',
        'last_imported_at' => 'datetime',
    ];

    /**
     * Get the enterprise that owns this company
     */
    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(MewsEnterprise::class, 'enterprise_id', 'mews_id');
    }

    /**
     * Get the mother company (parent company)
     */
    public function motherCompany(): BelongsTo
    {
        return $this->belongsTo(MewsCompany::class, 'mother_company_id', 'mews_id');
    }
}
