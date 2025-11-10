<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Room extends Model
{
    protected $fillable = [
        'room_type_id',
        'floor_id',
        'external_id',
        'name',
        'number',
        'description',
        'status',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(RoomAttribute::class);
    }

    // Helper relationship to get the hotel through room type
    public function hotel()
    {
        return $this->hasOneThrough(
            Hotel::class,
            RoomType::class,
            'id', // Foreign key on room_types table
            'id', // Foreign key on hotels table  
            'room_type_id', // Local key on rooms table
            'hotel_id' // Local key on room_types table
        );
    }

    // Helper attribute to get the hotel model
    public function getHotelAttribute()
    {
        return $this->roomType ? $this->roomType->hotel : null;
    }
}
