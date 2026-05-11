<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'book_id', 'member_id', 'reservation_date',
        'expiry_date', 'status', 'notes',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'expiry_date'      => 'date',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->status === 'pending' && $this->expiry_date->isPast();
    }
}
