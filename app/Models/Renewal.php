<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Renewal extends Model
{
    protected $fillable = [
        'borrowing_id', 'renewed_by', 'previous_due_date', 'new_due_date', 'notes',
    ];

    protected $casts = [
        'previous_due_date' => 'date',
        'new_due_date'      => 'date',
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function renewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renewed_by');
    }
}
