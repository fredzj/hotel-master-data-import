<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Hotel extends Model
{
    protected $table = 'transformed_hotels';

    protected $fillable = [
        'pms_system_id',
        'external_id',
        'code',
        'is_template',
        'name',
        'description',
        'company_name',
        'commercial_register_entry',
        'tax_id',
        'address',
        'city',
        'country',
        'postal_code',
        'phone',
        'email',
        'website',
        'timezone',
        'currency',
        'bank_iban',
        'bank_bic',
        'bank_name',
        'payment_terms',
        'status',
        'is_archived',
        'external_created_at',
    ];

    protected $casts = [
        'is_template' => 'boolean',
        'is_archived' => 'boolean',
        'payment_terms' => 'array',
        'external_created_at' => 'datetime',
    ];

    public function pmsSystem(): BelongsTo
    {
        return $this->belongsTo(PmsSystem::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }

    public function sunbedAreas(): HasMany
    {
        return $this->hasMany(SunbedArea::class);
    }

    // Relationship to get all rooms in this hotel through room types
    public function rooms(): HasManyThrough
    {
        return $this->hasManyThrough(Room::class, RoomType::class);
    }

    // Relationship to get all sunbeds in this hotel through sunbed areas
    public function sunbeds(): HasManyThrough
    {
        return $this->hasManyThrough(Sunbed::class, SunbedArea::class);
    }
}
