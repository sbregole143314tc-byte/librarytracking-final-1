@extends('layouts.app')
@section('title','Overdue Books')
@section('page-title','Overdue Books')
@section('breadcrumb','Books past their due date')

@section('content')
<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 bg-red-50 flex items-center gap-3">
        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <span class="font-semibold text-red-700">{{ $overdues->total() }} overdue transactions</span>
    </div>
    <table class="w-full data-table">
        <thead>
            <tr class="bg-gray-50">
                <th>Transaction</th><th>Book</th><th>Member</th><th>Due Date</th><th>Days Overdue</th><th>Estimated Fine</th><th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($overdues as $b)
            <tr class="bg-red-50/30">
                <td><a href="{{ route('borrowings.show', $b) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $b->transaction_code }}</a></td>
                <td><a href="{{ route('books.show', $b->book) }}" class="font-medium text-gray-800 hover:text-blue-700 text-sm">{{ $b->book->title }}</a></td>
                <td><a href="{{ route('members.show', $b->member) }}" class="text-gray-700 hover:text-blue-700 text-sm">{{ $b->member->name }}</a></td>
                <td class="text-red-600 font-semibold text-xs">{{ $b->due_date->format('M d, Y') }}</td>
                <td class="text-red-700 font-bold">{{ $b->days_overdue }}d</td>
                <td class="text-red-600 font-semibold text-xs">₱{{ number_format($b->calculateFine(), 2) }}</td>
                <td>
                    <a href="{{ route('borrowings.return', $b) }}" class="btn btn-primary text-xs py-1 px-3">Return</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-gray-400 py-10">
                <svg class="w-10 h-10 mx-auto mb-2 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                No overdue books! Great job.
            </td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $overdues->links() }}</div>
@endsection
