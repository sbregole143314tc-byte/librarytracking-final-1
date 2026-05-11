@extends('layouts.app')

@section('title', $member->name)
@section('page-title', $member->name)
@section('breadcrumb', $member->member_id . ' · ' . ucfirst($member->membership_type))

@section('header-actions')
    <a href="{{ route('members.edit', $member) }}" class="btn btn-outline">Edit</a>
    @if($member->is_active)
        <a href="{{ route('borrowings.create', ['member_id' => $member->id]) }}" class="btn btn-primary">Issue Book</a>
    @endif
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Member Profile --}}
    <div class="space-y-5">
        <div class="card p-6 text-center">
            <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 mx-auto mb-3 flex items-center justify-center">
                @if($member->photo)
                    <img src="{{ asset('storage/'.$member->photo) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-2xl font-bold text-gray-400">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                @endif
            </div>
            <h2 class="font-display text-lg text-gray-900">{{ $member->name }}</h2>
            <p class="text-xs font-mono text-gray-400 mt-0.5">{{ $member->member_id }}</p>
            <div class="mt-3">
                <span class="badge-{{ $member->status == 'active' ? 'active' : 'overdue' }}">{{ ucfirst($member->status) }}</span>
                <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 capitalize">{{ $member->membership_type }}</span>
            </div>

            @if($member->outstanding_fines > 0)
                <div class="mt-3 p-2 rounded-lg bg-red-50 text-red-700 text-sm">
                    Outstanding Fine: <strong>₱{{ number_format($member->outstanding_fines, 2) }}</strong>
                </div>
            @endif
        </div>

        <div class="card p-5">
            <h3 class="font-display text-sm text-gray-700 mb-3">Contact Info</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-xs text-gray-400">Email</dt><dd class="text-gray-700">{{ $member->email }}</dd></div>
                <div><dt class="text-xs text-gray-400">Phone</dt><dd>{{ $member->phone ?? '—' }}</dd></div>
                <div><dt class="text-xs text-gray-400">Address</dt><dd class="text-gray-600 text-xs">{{ $member->address ?? '—' }}</dd></div>
                <div><dt class="text-xs text-gray-400">Date of Birth</dt><dd>{{ $member->date_of_birth?->format('F j, Y') ?? '—' }}</dd></div>
            </dl>
        </div>

        <div class="card p-5">
            <h3 class="font-display text-sm text-gray-700 mb-3">Membership</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-xs text-gray-400">Start Date</dt><dd>{{ $member->membership_start->format('M d, Y') }}</dd></div>
                <div><dt class="text-xs text-gray-400">Expiry Date</dt>
                    <dd class="{{ $member->is_expired ? 'text-red-500 font-medium' : '' }}">{{ $member->membership_expiry->format('M d, Y') }}</dd>
                </div>
                <div><dt class="text-xs text-gray-400">Books Limit</dt><dd>{{ $member->max_books_allowed }} books</dd></div>
                <div><dt class="text-xs text-gray-400">Currently Borrowed</dt><dd class="font-semibold">{{ $member->current_books_count }}</dd></div>
            </dl>
            <form method="POST" action="{{ route('members.renew', $member) }}" class="mt-4">
                @csrf
                <button type="submit" class="btn btn-outline w-full justify-center text-xs">Renew Membership (+1 Year)</button>
            </form>
        </div>
    </div>

    {{-- Borrowing Activity --}}
    <div class="lg:col-span-2 space-y-5">

        @if($activeBorrowings->isNotEmpty())
        <div class="card">
            <div class="px-5 py-4 border-b border-gray-50">
                <h3 class="font-display text-sm text-gray-700">Currently Borrowed ({{ $activeBorrowings->count() }})</h3>
            </div>
            <table class="w-full data-table">
                <thead><tr><th>Book</th><th>Issued</th><th>Due Date</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($activeBorrowings as $b)
                <tr>
                    <td>
                        <a href="{{ route('books.show', $b->book) }}" class="font-medium text-gray-800 hover:text-blue-700">{{ $b->book->title }}</a>
                        <p class="text-xs text-gray-400">{{ $b->book->author }}</p>
                    </td>
                    <td class="text-xs text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</td>
                    <td class="{{ $b->is_overdue ? 'text-red-600 font-medium' : '' }} text-xs">
                        {{ $b->due_date->format('M d, Y') }}
                        @if($b->is_overdue)<br><span class="text-red-500">{{ $b->days_overdue }}d overdue</span>@endif
                    </td>
                    <td><span class="badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('borrowings.return', $b) }}" class="text-xs text-emerald-600 hover:underline">Return</a>
                            <a href="{{ route('borrowings.show', $b) }}" class="text-xs text-blue-600 hover:underline">View</a>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="card">
            <div class="px-5 py-4 border-b border-gray-50">
                <h3 class="font-display text-sm text-gray-700">Borrowing History</h3>
            </div>
            @if($history->isEmpty())
                <p class="text-center text-gray-400 text-sm py-8">No borrowing history yet.</p>
            @else
            <table class="w-full data-table">
                <thead><tr><th>Book</th><th>Borrowed</th><th>Returned</th><th>Fine</th></tr></thead>
                <tbody>
                @foreach($history as $b)
                <tr>
                    <td>
                        <a href="{{ route('books.show', $b->book) }}" class="text-gray-700 hover:text-blue-700 text-sm">{{ $b->book->title }}</a>
                    </td>
                    <td class="text-xs text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</td>
                    <td class="text-xs text-gray-500">{{ $b->return_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="{{ $b->fine_amount > 0 ? 'text-red-600 font-medium' : 'text-gray-400' }} text-xs">
                        {{ $b->fine_amount > 0 ? '₱'.number_format($b->fine_amount, 2) : '—' }}
                        @if($b->fine_paid)<span class="text-emerald-600"> (paid)</span>@endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>
</div>
@endsection
