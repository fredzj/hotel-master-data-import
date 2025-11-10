<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAttribute extends Model
{
    protected $fillable = [
        'room_id',
        'external_id',
        'name',
        'code',
        'description',
        'type',
        'value',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
