@extends('layouts.app')
@section('title','Reports')
@section('page-title','Reports & Analytics')

@section('content')

<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="form-label">From</label>
            <input type="date" name="from" value="{{ $from }}" class="form-input">
        </div>
        <div>
            <label class="form-label">To</label>
            <input type="date" name="to" value="{{ $to }}" class="form-input">
        </div>
        <button type="submit" class="btn btn-primary">Generate</button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:#EBF4FF">
            <svg class="w-6 h-6" style="color:var(--brand-mid)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold">{{ $borrowingsByType->sum() }}</p>
            <p class="text-xs text-gray-500">Total Transactions</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F0FDF4">
            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold">{{ $borrowingsByType->get('returned', 0) }}</p>
            <p class="text-xs text-gray-500">Returned</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FFF1F2">
            <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold">₱{{ number_format($finesCollected, 2) }}</p>
            <p class="text-xs text-gray-500">Fines Collected</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card">
        <div class="px-5 py-4 border-b border-gray-50"><h3 class="font-display text-sm text-gray-700">Top Borrowers</h3></div>
        <table class="w-full data-table">
            <thead><tr><th>Member</th><th>Type</th><th>Borrowings</th></tr></thead>
            <tbody>
            @foreach($topBorrowers as $m)
            <tr>
                <td><a href="{{ route('members.show', $m) }}" class="text-blue-600 hover:underline text-sm">{{ $m->name }}</a></td>
                <td><span class="text-xs capitalize px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">{{ $m->membership_type }}</span></td>
                <td class="font-semibold">{{ $m->borrowings_count }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="px-5 py-4 border-b border-gray-50"><h3 class="font-display text-sm text-gray-700">Most Borrowed Books</h3></div>
        <table class="w-full data-table">
            <thead><tr><th>Book</th><th>Category</th><th>Borrowings</th></tr></thead>
            <tbody>
            @foreach($topBooks as $b)
            <tr>
                <td><a href="{{ route('books.show', $b) }}" class="text-blue-600 hover:underline text-sm">{{ $b->title }}</a></td>
                <td class="text-xs text-gray-500">{{ $b->category }}</td>
                <td class="font-semibold">{{ $b->borrowings_count }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
