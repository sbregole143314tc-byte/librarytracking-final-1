@extends('layouts.admin')
@section('title','Overdue Books')
@section('page-title','Overdue Books')
@section('breadcrumb','Books past their due date — action required')

@section('content')
@if($overdues->total() > 0)
<div class="p-4 mb-5 rounded-2xl bg-red-50 border border-red-200 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <div>
            <p class="font-semibold text-red-800">{{ $overdues->total() }} overdue transactions</p>
            <p class="text-xs text-red-600">Total estimated fines: <strong>₱{{ number_format($totalFines, 2) }}</strong></p>
        </div>
    </div>
</div>
@endif

<div class="card mb-4 p-4">
    <form method="GET" class="flex gap-3 items-end">
        <div class="flex-1"><label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Member or book..." class="form-input">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full data-table">
        <thead><tr><th>Transaction</th><th>Book</th><th>Member</th><th>Due Date</th><th>Days Overdue</th><th>Fine</th><th></th></tr></thead>
        <tbody>
        @forelse($overdues as $b)
        <tr class="bg-red-50/40">
            <td class="font-mono text-xs"><a href="{{ route('borrowings.show',$b) }}" class="text-blue-600 hover:underline">{{ $b->transaction_code }}</a></td>
            <td>
                <a href="{{ route('books.show',$b->book) }}" class="font-medium text-gray-800 hover:text-blue-700 text-sm">{{ $b->book->title }}</a>
                <p class="text-xs text-gray-400">{{ $b->book->author }}</p>
            </td>
            <td>
                <a href="{{ route('members.show',$b->member) }}" class="text-gray-700 hover:text-blue-700 text-sm">{{ $b->member->name }}</a>
                <p class="text-xs text-gray-400">{{ $b->member->member_id }}</p>
            </td>
            <td class="text-red-700 font-semibold text-sm">{{ $b->due_date->format('M d, Y') }}</td>
            <td><span class="text-red-700 font-bold text-lg">{{ $b->days_overdue }}</span><span class="text-red-500 text-xs ml-1">days</span></td>
            <td class="text-red-700 font-bold text-sm">₱{{ number_format($b->calculateFine(),2) }}</td>
            <td><a href="{{ route('borrowings.return',$b) }}" class="btn btn-danger btn-sm">Process Return</a></td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center py-12">
            <svg class="w-12 h-12 mx-auto mb-3 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-gray-400 font-medium">No overdue books!</p>
        </td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $overdues->links() }}</div>
@endsection
