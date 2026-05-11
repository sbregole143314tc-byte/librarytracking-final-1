@extends('layouts.admin')
@section('title','All Transactions')
@section('page-title','All Borrowing Transactions')
@section('header-actions')
    <a href="{{ route('borrowings.create') }}" class="btn btn-primary btn-sm">+ Issue Book</a>
@endsection

@section('content')

{{-- Quick stat tabs --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <a href="{{ route('borrowings.index') }}" class="stat-card hover:shadow-md transition-shadow {{ !request()->get('status') ? 'ring-2 ring-blue-400' : '' }}">
        <div class="stat-icon bg-blue-50"><svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
        <div><p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p><p class="text-xs text-gray-500">Total</p></div>
    </a>
    <a href="{{ route('borrowings.index', ['status'=>'active']) }}" class="stat-card hover:shadow-md transition-shadow {{ request('status')=='active' ? 'ring-2 ring-emerald-400' : '' }}">
        <div class="stat-icon bg-emerald-50"><svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></div>
        <div><p class="text-xl font-bold text-gray-900">{{ $stats['active'] }}</p><p class="text-xs text-gray-500">Active</p></div>
    </a>
    <a href="{{ route('borrowings.overdue') }}" class="stat-card hover:shadow-md transition-shadow">
        <div class="stat-icon bg-red-50"><svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <div><p class="text-xl font-bold text-gray-900">{{ $stats['overdue'] }}</p><p class="text-xs text-red-500">Overdue</p></div>
    </a>
    <a href="{{ route('borrowings.index', ['status'=>'returned']) }}" class="stat-card hover:shadow-md transition-shadow {{ request('status')=='returned' ? 'ring-2 ring-gray-400' : '' }}">
        <div class="stat-icon bg-gray-50"><svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <div><p class="text-xl font-bold text-gray-900">{{ $stats['returned'] }}</p><p class="text-xs text-gray-500">Returned</p></div>
    </a>
</div>

<div class="card mb-4 p-4">
    <form method="GET" class="flex gap-3 items-end flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Member, book, or transaction code..." class="form-input">
        </div>
        <div class="w-36">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="">All</option>
                @foreach(['active','overdue','returned','lost'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('borrowings.index') }}" class="btn btn-outline btn-sm">Clear</a>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full data-table">
        <thead><tr><th>Code</th><th>Book</th><th>Member</th><th>Issued</th><th>Due</th><th>Status</th><th>Fine</th><th></th></tr></thead>
        <tbody>
        @forelse($borrowings as $b)
        <tr class="{{ $b->is_overdue ? 'bg-red-50/40' : '' }}">
            <td class="font-mono text-xs"><a href="{{ route('borrowings.show', $b) }}" class="text-blue-600 hover:underline">{{ $b->transaction_code }}</a></td>
            <td><a href="{{ route('books.show', $b->book) }}" class="font-medium text-gray-800 hover:text-blue-700 text-xs">{{ Str::limit($b->book->title,30) }}</a></td>
            <td><a href="{{ route('members.show', $b->member) }}" class="text-gray-700 hover:text-blue-700 text-xs">{{ $b->member->name }}</a></td>
            <td class="text-xs text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</td>
            <td class="{{ $b->is_overdue?'text-red-600 font-semibold':'' }} text-xs">
                {{ $b->due_date->format('M d, Y') }}
                @if($b->is_overdue)<br><span class="text-red-400 font-normal">{{ $b->days_overdue }}d late</span>@endif
            </td>
            <td><span class="badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
            <td class="{{ $b->fine_amount>0&&!$b->fine_paid?'text-red-600 font-semibold':' text-gray-400' }} text-xs">
                {{ $b->fine_amount>0 ? '₱'.number_format($b->fine_amount,2) : '—' }}
            </td>
            <td>
                <div class="flex gap-2">
                    <a href="{{ route('borrowings.show', $b) }}" class="text-xs text-blue-600 hover:underline">View</a>
                    @if(in_array($b->status,['active','overdue']))
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
