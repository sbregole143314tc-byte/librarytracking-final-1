<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{

    public function index(): View
    {
        $stats = [
            'total_books'       => Book::count(),
            'available_books'   => Book::available()->count(),
            'total_members'     => Member::count(),
            'active_members'    => Member::active()->count(),
            'active_borrowings' => Borrowing::whereIn('status', ['active', 'overdue'])->count(),
            'overdue_count'     => Borrowing::where(fn($q) =>
                $q->where('status', 'overdue')
                  ->orWhere(fn($q2) => $q2->where('status', 'active')->where('due_date', '<', now()->toDateString()))
            )->count(),
            'fines_pending'     => Borrowing::where('fine_amount', '>', 0)->where('fine_paid', false)->sum('fine_amount'),
        ];

        $recentBorrowings = Borrowing::with(['book', 'member'])
            ->orderByDesc('created_at')->limit(8)->get();

        $dueSoon = Borrowing::with(['book', 'member'])
            ->where('status', 'active')
            ->whereBetween('due_date', [now()->toDateString(), now()->addDays(3)->toDateString()])
            ->orderBy('due_date')->get();

        $overdueList = Borrowing::with(['book', 'member'])
            ->where(fn($q) => $q->where('status', 'overdue')
                ->orWhere(fn($q2) => $q2->where('status', 'active')->where('due_date', '<', now()->toDateString())))
            ->orderBy('due_date')->limit(5)->get();

        $monthlyData = Borrowing::selectRaw("DATE_FORMAT(borrow_date, '%Y-%m') as month, COUNT(*) as count")
            ->where('borrow_date', '>=', now()->subMonths(6)->toDateString())
            ->groupBy('month')->orderBy('month')
            ->pluck('count', 'month');

        $popularBooks = Book::withCount('borrowings')
            ->orderByDesc('borrowings_count')->limit(5)->get();

        $categoryStats = Book::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')->orderByDesc('count')->get();

        return view('admin.dashboard.index', compact(
            'stats', 'recentBorrowings', 'dueSoon', 'overdueList',
            'monthlyData', 'popularBooks', 'categoryStats'
        ));
    }

    public function reports(Request $request): View
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $borrowingsByType = Borrowing::selectRaw('COUNT(*) as count, status')
            ->whereBetween('borrow_date', [$from, $to])
            ->groupBy('status')->pluck('count', 'status');

        $finesCollected = Borrowing::whereBetween('fine_paid_date', [$from, $to])
            ->where('fine_paid', true)->sum('fine_amount');

        $topBorrowers = Member::withCount(['borrowings' => fn($q) => $q->whereBetween('borrow_date', [$from, $to])])
            ->orderByDesc('borrowings_count')->limit(10)->get();

        $topBooks = Book::withCount(['borrowings' => fn($q) => $q->whereBetween('borrow_date', [$from, $to])])
            ->orderByDesc('borrowings_count')->limit(10)->get();

        return view('admin.dashboard.reports', compact(
            'from', 'to', 'borrowingsByType', 'finesCollected', 'topBorrowers', 'topBooks'
        ));
    }
}
