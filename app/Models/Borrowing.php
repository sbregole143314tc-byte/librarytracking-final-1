<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code', 'book_id', 'member_id', 'issued_by',
        'returned_to', 'borrow_date', 'due_date', 'return_date',
        'status', 'fine_amount', 'fine_paid', 'fine_paid_date',
        'condition_on_return', 'notes',
    ];

    protected $casts = [
        'borrow_date'    => 'date',
        'due_date'       => 'date',
        'return_date'    => 'date',
        'fine_paid_date' => 'date',
        'fine_amount'    => 'decimal:2',
        'fine_paid'      => 'boolean',
    ];

    // ─── Boot ────────────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Borrowing $b) {
            if (empty($b->transaction_code)) {
                $b->transaction_code = static::generateCode();
            }
        });
    }

    // ─── Relationships ──────────────────────────────────────────────────────

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function returnedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(Renewal::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', 'overdue')
                     ->orWhere(function ($q) {
                         $q->where('status', 'active')
                           ->where('due_date', '<', now()->toDateString());
                     });
    }

    public function scopePendingFines(Builder $query): Builder
    {
        return $query->where('fine_amount', '>', 0)->where('fine_paid', false);
    }

    // ─── Accessors ──────────────────────────────────────────────────────────

    public function getIsOverdueAttribute(): bool
    {
        return in_array($this->status, ['active', 'overdue'])
               && $this->due_date->isPast();
    }

    public function getDaysOverdueAttribute(): int
    {
        if (! $this->is_overdue) {
            return 0;
        }
        $endDate = $this->return_date ?? now();
        return $this->due_date->diffInDays($endDate);
    }

    public function getDaysRemainingAttribute(): int
    {
        if ($this->status !== 'active') return 0;
        return max(0, now()->diffInDays($this->due_date, false));
    }

    public function getRenewalCountAttribute(): int
    {
        return $this->renewals()->count();
    }

    // ─── Helper Methods ──────────────────────────────────────────────────────

    public static function generateCode(): string
    {
        return 'BRW-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function calculateFine(): float
    {
        $settings = FineSetting::first();
        if (! $settings || ! $this->is_overdue) return 0.0;

        $daysOver = $this->days_overdue - $settings->grace_period_days;

        if ($daysOver <= 0) return 0.0;

        return round($daysOver * $settings->daily_fine_rate, 2);
    }

    public function canRenew(): bool
    {
        $settings = FineSetting::first();
        $limit    = $settings?->renewal_limit ?? 2;

        return $this->status === 'active'
               && $this->renewal_count < $limit
               && ! $this->is_overdue;
    }
}
