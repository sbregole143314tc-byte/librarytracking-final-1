<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowingRequest;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Services\BorrowingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function __construct(private BorrowingService $service) {}

    public function index(Request $request): View
    {
        $query = Borrowing::with(['book', 'member']);
        if ($status = $request->get('status')) { $query->where('status', $status); }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', fn($m) => $m->search($search))
                  ->orWhereHas('book', fn($b) => $b->search($search))
                  ->orWhere('transaction_code', 'like', "%{$search}%");
            });
        }
        $borrowings = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $stats = [
            'total'    => Borrowing::count(),
            'active'   => Borrowing::where('status', 'active')->count(),
            'overdue'  => Borrowing::where(fn($q) => $q->where('status','overdue')->orWhere(fn($q2) => $q2->where('status','active')->where('due_date','<',now()->toDateString())))->count(),
            'returned' => Borrowing::where('status', 'returned')->count(),
        ];
        return view('admin.borrowings.index', compact('borrowings', 'stats'));
    }

    public function borrowed(Request $request): View
    {
        $query = Borrowing::with(['book', 'member'])->whereIn('status', ['active', 'overdue']);
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', fn($m) => $m->search($search))
                  ->orWhereHas('book', fn($b) => $b->search($search));
            });
        }
        $borrowings = $query->orderBy('due_date')->paginate(20)->withQueryString();
        return view('admin.borrowings.borrowed', compact('borrowings'));
    }

    public function available(Request $request): View
    {
        $query = Book::where('status', 'active')->where('available_copies', '>', 0);
        if ($search = $request->get('search')) { $query->search($search); }
        if ($category = $request->get('category')) { $query->byCategory($category); }
        $books      = $query->orderBy('title')->paginate(20)->withQueryString();
        $categories = Book::getCategories();
        return view('admin.borrowings.available', compact('books', 'categories'));
    }

    public function overdue(Request $request): View
    {
        $query = Borrowing::with(['book', 'member'])
            ->where(fn($q) => $q->where('status', 'overdue')
                ->orWhere(fn($q2) => $q2->where('status', 'active')->where('due_date', '<', now()->toDateString())));
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', fn($m) => $m->search($search))
                  ->orWhereHas('book', fn($b) => $b->search($search));
            });
        }
        $overdues   = $query->orderBy('due_date')->paginate(20)->withQueryString();
        $totalFines = $overdues->sum(fn($b) => $b->calculateFine());
        return view('admin.borrowings.overdue', compact('overdues', 'totalFines'));
    }

    public function create(Request $request): View
    {
        $member  = $request->get('member_id') ? Member::find($request->get('member_id')) : null;
        $book    = $request->get('book_id')   ? Book::find($request->get('book_id'))     : null;
        $members = Member::active()->orderBy('name')->get();
        $books   = Book::available()->orderBy('title')->get();
        return view('admin.borrowings.create', compact('member', 'book', 'members', 'books'));
    }

    public function store(StoreBorrowingRequest $request): RedirectResponse
    {
        $member    = Member::findOrFail($request->member_id);
        $book      = Book::findOrFail($request->book_id);
        $borrowing = $this->service->issueBook($member, $book, auth()->id(), ['notes' => $request->notes]);
        return redirect()->route('borrowings.show', $borrowing)
                         ->with('success', "Book issued to {$member->name}. Due: {$borrowing->due_date->format('M d, Y')}.");
    }

    public function show(Borrowing $borrowing): View
    {
        $borrowing->load(['book', 'member', 'issuedBy', 'returnedTo', 'renewals.renewedBy']);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function returnBook(Borrowing $borrowing): View
    {
        $borrowing->load(['book', 'member']);
        $fine = $borrowing->calculateFine();
        return view('admin.borrowings.return', compact('borrowing', 'fine'));
    }

    public function processReturn(Request $request, Borrowing $borrowing): RedirectResponse
    {
        $request->validate(['condition' => 'required|in:good,damaged,lost', 'notes' => 'nullable|string|max:500']);
        $borrowing = $this->service->returnBook($borrowing, auth()->id(), $request->only('condition', 'notes'));
        $msg = 'Book returned successfully.';
        if ($borrowing->fine_amount > 0) {
            $msg .= " Fine of ₱" . number_format($borrowing->fine_amount, 2) . " added.";
        }
        return redirect()->route('borrowings.show', $borrowing)->with('success', $msg);
    }

    public function renew(Borrowing $borrowing): RedirectResponse
    {
        $renewal = $this->service->renewBook($borrowing, auth()->id());
        return back()->with('success', "Loan renewed until {$renewal->new_due_date->format('M d, Y')}.");
    }

    public function payFine(Borrowing $borrowing): RedirectResponse
    {
        $this->service->payFine($borrowing);
        return back()->with('success', "Fine of ₱" . number_format($borrowing->fine_amount, 2) . " marked as paid.");
    }
}
