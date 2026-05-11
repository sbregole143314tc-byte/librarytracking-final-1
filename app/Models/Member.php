<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    const MAX_BOOKS = 4;

    protected $fillable = [
        'user_id', 'member_id', 'name', 'email', 'phone', 'address',
        'date_of_birth', 'membership_type', 'membership_start',
        'membership_expiry', 'status', 'outstanding_fines',
        'max_books_allowed', 'photo', 'notes',
    ];

    protected $casts = [
        'date_of_birth'     => 'date',
        'membership_start'  => 'date',
        'membership_expiry' => 'date',
        'outstanding_fines' => 'decimal:2',
        'max_books_allowed' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Member $member) {
            if (empty($member->member_id)) {
                $member->member_id = static::generateMemberId();
            }
            if ($member->max_books_allowed > self::MAX_BOOKS) {
                $member->max_books_allowed = self::MAX_BOOKS;
            }
        });
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function borrowings(): HasMany { return $this->hasMany(Borrowing::class); }
    public function activeBorrowings(): HasMany {
        return $this->hasMany(Borrowing::class)->whereIn('status', ['active', 'overdue']);
    }
    public function reservations(): HasMany { return $this->hasMany(Reservation::class); }

    public function scopeActive(Builder $query): Builder { return $query->where('status', 'active'); }
    public function scopeSearch(Builder $query, string $term): Builder {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('member_id', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function getIsActiveAttribute(): bool {
        return $this->status === 'active' && $this->membership_expiry->isFuture();
    }
    public function getCanBorrowAttribute(): bool {
        return $this->is_active
            && $this->outstanding_fines == 0
            && $this->activeBorrowings()->count() < min($this->max_books_allowed, self::MAX_BOOKS);
    }
    public function getIsExpiredAttribute(): bool { return $this->membership_expiry->isPast(); }
    public function getPhotoUrlAttribute(): string {
        return $this->photo ? asset('storage/' . $this->photo) : asset('assets/images/default-avatar.png');
    }
    public function getCurrentBooksCountAttribute(): int { return $this->activeBorrowings()->count(); }
    public function getHasOverdueAttribute(): bool {
        return $this->activeBorrowings()
            ->where(fn($q) => $q->where('status','overdue')
                ->orWhere(fn($q2) => $q2->where('status','active')->where('due_date','<',now()->toDateString())))
            ->exists();
    }
    public function getBooksRemainingAttribute(): int {
        return max(0, min($this->max_books_allowed, self::MAX_BOOKS) - $this->current_books_count);
    }

    public static function generateMemberId(): string {
        $prefix = 'LIB';
        $year   = now()->format('Y');
        $last   = static::withTrashed()->count() + 1;
        return sprintf('%s-%s-%05d', $prefix, $year, $last);
    }
}
