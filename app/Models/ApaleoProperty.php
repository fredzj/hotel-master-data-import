<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApaleoProperty extends Model
{
    protected $fillable = [
        'apaleo_id',
        'name',
        'code',
        'description',
        'status',
        'country_code',
        'city',
        'postal_code',
        'address_line1',
        'address_line2',
        'state',
        'company_name',
        'tax_id',
        'commercial_register_entry',
        'iban',
        'bic',
        'bank_name',
        'timezone',
        'currency_code',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];

    public function unitGroups(): HasMany
    {
        return $this->hasMany(ApaleoUnitGroup::class, 'property_id', 'apaleo_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(ApaleoUnit::class, 'property_id', 'apaleo_id');
    }
}