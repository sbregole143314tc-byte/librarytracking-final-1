<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FineSetting extends Model
{
    protected $fillable = [
        'daily_fine_rate', 'lost_book_multiplier',
        'grace_period_days', 'max_borrow_days', 'renewal_limit',
    ];

    protected $casts = [
        'daily_fine_rate'      => 'decimal:2',
        'lost_book_multiplier' => 'decimal:2',
        'grace_period_days'    => 'integer',
        'max_borrow_days'      => 'integer',
        'renewal_limit'        => 'integer',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'daily_fine_rate'      => 5.00,
            'lost_book_multiplier' => 10.00,
            'grace_period_days'    => 0,
            'max_borrow_days'      => 14,
            'renewal_limit'        => 2,
        ]);
    }
}
