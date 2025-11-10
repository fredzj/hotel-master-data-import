<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApaleoService extends Model
{
    protected $table = 'apaleo_services';
    
    protected $fillable = [
        'apaleo_id',
        'property_id',
        'code',
        'name',
        'description',
        'raw_data'
    ];

    protected $casts = [
        'raw_data' => 'array'
    ];

    /**
     * Get the property this service belongs to
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(ApaleoProperty::class, 'property_id', 'apaleo_id');
    }


}
