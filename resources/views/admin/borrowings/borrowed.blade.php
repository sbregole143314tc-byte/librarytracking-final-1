@extends('layouts.admin')
@section('title','Currently Borrowed')
@section('page-title','Currently Borrowed Books')
@section('breadcrumb','All books currently checked out')

@section('content')
<div class="card mb-4 p-4">
    <form method="GET" class="flex gap-3 items-end">
        <div class="flex-1"><label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Member or book..." class="form-input">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        <a href="{{ route('borrowings.borrowed') }}" class="btn btn-outline btn-sm">Clear</a>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="px-5 py-3 bg-blue-50 border-b border-blue-100 flex items-center justify-between">
        <p class="text-sm font-semibold text-blue-800">{{ $borrowings->total() }} books currently checked out</p>
        <a href="{{ route('borrowings.create') }}" class="btn btn-primary btn-sm">Issue Another</a>
    </div>
    <table class="w-full data-table">
        <thead><tr><th>Book</th><th>Member</th><th>Member ID</th><th>Borrowed On</th><th>Due Date</th><th>Days Left</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($borrowings as $b)
        <tr class="{{ $b->is_overdue ? 'bg-red-50/50' : '' }}">
            <td>
                <a href="{{ route('books.show', $b->book) }}" class="font-medium text-gray-800 hover:text-blue-700 text-sm">{{ $b->book->title }}</a>
                <p class="text-xs text-gray-400">{{ $b->book->author }}</p>
            </td>
            <td><a href="{{ route('members.show', $b->member) }}" class="text-gray-700 hover:text-blue-700 text-sm">{{ $b->member->name }}</a></td>
            <td class="font-mono text-xs text-gray-500">{{ $b->member->member_id }}</td>
            <td class="text-xs text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</td>
            <td class="{{ $b->is_overdue?'text-red-600 font-bold':'' }} text-sm">{{ $b->due_date->format('M d, Y') }}</td>
            <td>
                @if($b->is_overdue)
                    <span class="text-red-600 font-bold text-sm">{{ $b->days_overdue }}d LATE</span>
                @else
                    <span class="{{ $b->days_remaining <= 2 ? 'text-amber-600 font-semibold' : 'text-gray-600' }} text-sm">{{ $b->days_remaining }}d</span>
                @endif
            </td>
            <td><span class="badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
            <td>
                <div class="flex gap-2">
                    <a href="{{ route('borrowings.show', $b) }}" class="text-xs text-blue-600 hover:underline">View</a>
                    <a href="{{ route('borrowings.return', $b) }}" class="text-xs text-emerald-600 hover:underline">Return</a>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-gray-400 py-10">No books currently borrowed.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $borrowings->links() }}</div>
@endsection
