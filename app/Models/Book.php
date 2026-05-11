<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'isbn', 'author', 'publisher', 'published_year',
        'category', 'description', 'cover_image', 'total_copies',
        'available_copies', 'price', 'location', 'status',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'total_copies'   => 'integer',
        'available_copies' => 'integer',
        'price'          => 'decimal:2',
    ];

    // ─── Relationships ──────────────────────────────────────────────────────

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class)->whereIn('status', ['active', 'overdue']);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('available_copies', '>', 0)->where('status', 'active');
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('author', 'like', "%{$term}%")
              ->orWhere('isbn', 'like', "%{$term}%")
              ->orWhere('publisher', 'like', "%{$term}%");
        });
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    // ─── Accessors ──────────────────────────────────────────────────────────

    public function getIsAvailableAttribute(): bool
    {
        return $this->available_copies > 0 && $this->status === 'active';
    }

    public function getBorrowedCopiesAttribute(): int
    {
        return $this->total_copies - $this->available_copies;
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('assets/images/default-book.png');
    }

    // ─── Helper Methods ──────────────────────────────────────────────────────

    public function decrementAvailable(): void
    {
        $this->decrement('available_copies');
    }

    public function incrementAvailable(): void
    {
        if ($this->available_copies < $this->total_copies) {
            $this->increment('available_copies');
        }
    }

    public static function getCategories(): array
    {
        return self::distinct()->orderBy('category')->pluck('category')->toArray();
    }
}
