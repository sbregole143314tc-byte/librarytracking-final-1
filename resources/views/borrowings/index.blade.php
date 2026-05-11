@extends('layouts.app')

@section('title', 'Borrowings')
@section('page-title', 'Borrowing Transactions')

@section('header-actions')
    <a href="{{ route('borrowings.create') }}" class="btn btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Issue Book
    </a>
@endsection

@section('content')

<div class="card p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Member, book, or transaction code..." class="form-input">
        </div>
        <div class="w-36">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="">All</option>
                @foreach(['active','overdue','returned','lost'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2 pb-0.5">
            <input type="checkbox" name="overdue" value="1" id="overdue" {{ request('overdue') ? 'checked' : '' }} class="rounded">
            <label for="overdue" class="text-sm text-gray-600">Overdue only</label>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('borrowings.index') }}" class="btn btn-outline">Clear</a>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full data-table">
        <thead>
            <tr class="bg-gray-50">
                <th>Transaction</th>
                <th>Book</th>
                <th>Member</th>
                <th>Issued</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Fine</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($borrowings as $b)
            <tr class="{{ $b->is_overdue ? 'bg-red-50/40' : '' }}">
                <td>
                    <a href="{{ route('borrowings.show', $b) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $b->transaction_code }}</a>
                </td>
                <td class="max-w-[180px]">
                    <a href="{{ route('books.show', $b->book) }}" class="text-gray-800 hover:text-blue-700 font-medium text-sm truncate block">{{ $b->book->title }}</a>
                    <span class="text-xs text-gray-400">{{ $b->book->author }}</span>
                </td>
                <td>
                    <a href="{{ route('members.show', $b->member) }}" class="text-gray-700 hover:text-blue-700 text-sm">{{ $b->member->name }}</a>
                </td>
                <td class="text-xs text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</td>
                <td class="{{ $b->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-600' }} text-xs">
                    {{ $b->due_date->format('M d, Y') }}
                    @if($b->is_overdue)
                        <br><span class="text-red-500 font-normal">{{ $b->days_overdue }}d late</span>
                    @elseif($b->status === 'active')
                        <br><span class="text-gray-400 font-normal">{{ $b->days_remaining }}d left</span>
                    @endif
                </td>
                <td><span class="badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                <td class="{{ $b->fine_amount > 0 && !$b->fine_paid ? 'text-red-600 font-semibold' : 'text-gray-400' }} text-xs">
                    {{ $b->fine_amount > 0 ? '₱'.number_format($b->fine_amount, 2) : '—' }}
                    @if($b->fine_paid)<br><span class="text-emerald-600 font-normal">paid</span>@endif
                </td>
                <td>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('borrowings.show', $b) }}" class="text-xs text-blue-600 hover:underline">View</a>
                        @if(in_array($b->status, ['active', 'overdue']))
                            <a href="{{ route('borrowings.return', $b) }}" class="text-xs text-emerald-600 hover:underline">Return</a>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-gray-400 py-8">No transactions found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $borrowings->links() }}</div>

@endsection
