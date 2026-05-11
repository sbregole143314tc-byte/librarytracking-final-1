<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPortalController extends Controller
{

    private function getMember()
    {
        return auth()->user()->member;
    }

    // ── My Dashboard ─────────────────────────────────────────────────────────
    public function dashboard(): View
    {
        $member = $this->getMember();
        $member->load(['activeBorrowings.book', 'borrowings' => fn($q) => $q->where('status', 'returned')->latest()->limit(5)->with('book')]);

        $activeBorrowings = $member->activeBorrowings;

        // Separate overdue from on-time
        $overdueBorrowings = $activeBorrowings->filter(fn($b) =>
            $b->status === 'overdue' || ($b->status === 'active' && $b->due_date->isPast())
        );
        $currentBorrowings = $activeBorrowings->filter(fn($b) =>
            $b->status === 'active' && ! $b->due_date->isPast()
        );

        $dueSoon = $currentBorrowings->filter(fn($b) => $b->due_date->diffInDays(now(), false) >= -3);

        $recentHistory = $member->borrowings->where('status', 'returned');

        return view('user.dashboard', compact(
            'member', 'activeBorrowings', 'overdueBorrowings',
            'currentBorrowings', 'dueSoon', 'recentHistory'
        ));
    }

    // ── My Borrowed Books ────────────────────────────────────────────────────
    public function myBooks(): View
    {
        $member = $this->getMember();

        $activeBorrowings = $member->activeBorrowings()->with('book')->orderBy('due_date')->get();
        $overdueBorrowings = $activeBorrowings->filter(fn($b) =>
            $b->status === 'overdue' || ($b->status === 'active' && $b->due_date->isPast())
        );

        return view('user.my-books', compact('member', 'activeBorrowings', 'overdueBorrowings'));
    }

    // ── Overdue Books ────────────────────────────────────────────────────────
    public function overdue(): View
    {
        $member = $this->getMember();

        $overdueBorrowings = $member->activeBorrowings()
            ->with('book')
            ->where(fn($q) => $q->where('status', 'overdue')
                ->orWhere(fn($q2) => $q2->where('status', 'active')->where('due_date', '<', now()->toDateString())))
            ->orderBy('due_date')
            ->get();

        return view('user.overdue', compact('member', 'overdueBorrowings'));
    }

    // ── Available Books ──────────────────────────────────────────────────────
    public function availableBooks(Request $request): View
    {
        $member = $this->getMember();
        $query  = Book::available();

        if ($search = $request->get('search')) {
            $query->search($search);
        }
        if ($category = $request->get('category')) {
            $query->byCategory($category);
        }

        $books      = $query->orderBy('title')->paginate(12)->withQueryString();
        $categories = Book::getCategories();

        return view('user.available-books', compact('member', 'books', 'categories'));
    }

    // ── Borrowing History ────────────────────────────────────────────────────
    public function history(): View
    {
        $member  = $this->getMember();
        $history = $member->borrowings()
            ->with('book')
            ->where('status', 'returned')
            ->orderByDesc('return_date')
            ->paginate(15);

        return view('user.history', compact('member', 'history'));
    }
}
