<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\FineSetting;
use App\Models\Member;
use App\Models\Renewal;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BorrowingService
{
    public function issueBook(Member $member, Book $book, int $issuedBy, array $options = []): Borrowing
    {
        return DB::transaction(function () use ($member, $book, $issuedBy, $options) {
            // Validate member
            if (! $member->is_active) {
                throw ValidationException::withMessages(['member' => 'Member is not active or membership has expired.']);
            }
            if ($member->outstanding_fines > 0) {
                throw ValidationException::withMessages(['member' => 'Member has outstanding fines of ₱' . number_format($member->outstanding_fines, 2) . '.']);
            }
            if ($member->current_books_count >= $member->max_books_allowed) {
                throw ValidationException::withMessages(['member' => "Member has reached the maximum borrow limit ({$member->max_books_allowed} books)."]);
            }

            // Validate book
            if (! $book->is_available) {
                throw ValidationException::withMessages(['book' => 'This book is not available for borrowing.']);
            }

            $settings = FineSetting::current();
            $borrowDate = now()->toDateString();
            $dueDate    = now()->addDays($options['borrow_days'] ?? $settings->max_borrow_days)->toDateString();

            // Create borrowing record
            $borrowing = Borrowing::create([
                'book_id'    => $book->id,
                'member_id'  => $member->id,
                'issued_by'  => $issuedBy,
                'borrow_date' => $borrowDate,
                'due_date'   => $dueDate,
                'status'     => 'active',
                'notes'      => $options['notes'] ?? null,
            ]);

            // Decrement available copies
            $book->decrementAvailable();

            return $borrowing;
        });
    }

    public function returnBook(Borrowing $borrowing, int $returnedBy, array $options = []): Borrowing
    {
        return DB::transaction(function () use ($borrowing, $returnedBy, $options) {
            if (! in_array($borrowing->status, ['active', 'overdue'])) {
                throw ValidationException::withMessages(['borrowing' => 'This book has already been returned.']);
            }

            $condition  = $options['condition'] ?? 'good';
            $returnDate = now()->toDateString();
            $fineAmount = $borrowing->calculateFine();

            // Extra fine for lost / damaged
            if ($condition === 'lost') {
                $settings    = FineSetting::current();
                $fineAmount += ($borrowing->book->price ?? 0) * $settings->lost_book_multiplier;
            }

            $borrowing->update([
                'return_date'        => $returnDate,
                'returned_to'        => $returnedBy,
                'status'             => 'returned',
                'condition_on_return' => $condition,
                'fine_amount'        => $fineAmount,
                'notes'              => $options['notes'] ?? $borrowing->notes,
            ]);

            // Return the book copy
            if ($condition !== 'lost') {
                $borrowing->book->incrementAvailable();
                if ($condition === 'damaged') {
                    $borrowing->book->update(['status' => 'damaged']);
                }
            }

            // Update member's outstanding fines
            if ($fineAmount > 0) {
                $borrowing->member->increment('outstanding_fines', $fineAmount);
            }

            return $borrowing->fresh();
        });
    }

    public function renewBook(Borrowing $borrowing, int $renewedBy, array $options = []): Renewal
    {
        return DB::transaction(function () use ($borrowing, $renewedBy, $options) {
            if (! $borrowing->canRenew()) {
                throw ValidationException::withMessages(['borrowing' => 'This borrowing cannot be renewed (limit reached or overdue).']);
            }

            $settings        = FineSetting::current();
            $previousDueDate = $borrowing->due_date->toDateString();
            $newDueDate      = $borrowing->due_date->addDays($settings->max_borrow_days)->toDateString();

            $borrowing->update(['due_date' => $newDueDate]);

            return Renewal::create([
                'borrowing_id'     => $borrowing->id,
                'renewed_by'       => $renewedBy,
                'previous_due_date' => $previousDueDate,
                'new_due_date'     => $newDueDate,
                'notes'            => $options['notes'] ?? null,
            ]);
        });
    }

    public function payFine(Borrowing $borrowing): Borrowing
    {
        return DB::transaction(function () use ($borrowing) {
            if ($borrowing->fine_paid) {
                throw ValidationException::withMessages(['fine' => 'Fine has already been paid.']);
            }

            $borrowing->update([
                'fine_paid'      => true,
                'fine_paid_date' => now()->toDateString(),
            ]);

            $borrowing->member->decrement('outstanding_fines', $borrowing->fine_amount);

            return $borrowing->fresh();
        });
    }

    public function markOverdue(): int
    {
        return Borrowing::where('status', 'active')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);
    }
}
