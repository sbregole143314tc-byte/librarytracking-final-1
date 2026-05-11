@extends('layouts.app')

@section('title', $book->title)
@section('page-title', $book->title)
@section('breadcrumb', $book->author . ' · ' . $book->category)

@section('header-actions')
    <a href="{{ route('books.edit', $book) }}" class="btn btn-outline">Edit</a>
    @if($book->is_available)
        <a href="{{ route('borrowings.create', ['book_id' => $book->id]) }}" class="btn btn-primary">Issue Book</a>
    @endif
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Book Details --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="card p-6 flex gap-6">
            <div class="w-28 h-36 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 flex items-center justify-center shadow">
                @if($book->cover_image)
                    <img src="{{ asset('storage/'.$book->cover_image) }}" class="w-full h-full object-cover" alt="{{ $book->title }}">
                @else
                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                @endif
            </div>
            <div>
                <h2 class="font-display text-2xl text-gray-900 leading-tight">{{ $book->title }}</h2>
                <p class="text-gray-500 mt-1">by {{ $book->author }}</p>
                @if($book->description)
                    <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ $book->description }}</p>
                @endif
            </div>
        </div>

        <div class="card p-5">
            <h3 class="font-display text-sm text-gray-700 mb-3">Book Details</h3>
            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <div><dt class="text-gray-400 text-xs">ISBN</dt><dd class="font-mono">{{ $book->isbn }}</dd></div>
                <div><dt class="text-gray-400 text-xs">Publisher</dt><dd>{{ $book->publisher ?? '—' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">Year</dt><dd>{{ $book->published_year ?? '—' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">Category</dt><dd>{{ $book->category }}</dd></div>
                <div><dt class="text-gray-400 text-xs">Location</dt><dd>{{ $book->location ?? '—' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">Price</dt><dd>{{ $book->price ? '₱'.number_format($book->price, 2) : '—' }}</dd></div>
                <div><dt class="text-gray-400 text-xs">Total Copies</dt><dd>{{ $book->total_copies }}</dd></div>
                <div><dt class="text-gray-400 text-xs">Available</dt><dd class="{{ $book->available_copies > 0 ? 'text-emerald-600 font-semibold' : 'text-red-600 font-semibold' }}">{{ $book->available_copies }}</dd></div>
                <div><dt class="text-gray-400 text-xs">Status</dt><dd><span class="badge-{{ $book->status == 'active' ? 'active' : 'overdue' }}">{{ ucfirst($book->status) }}</span></dd></div>
            </dl>
        </div>

        {{-- Active Borrowings --}}
        @if($activeBorrowings->isNotEmpty())
        <div class="card">
            <div class="px-5 py-4 border-b border-gray-50">
                <h3 class="font-display text-sm text-gray-700">Currently Borrowed</h3>
            </div>
            <table class="w-full data-table">
                <thead><tr><th>Member</th><th>Issued</th><th>Due</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($activeBorrowings as $b)
                <tr>
                    <td><a href="{{ route('members.show', $b->member) }}" class="text-blue-600 hover:underline">{{ $b->member->name }}</a></td>
                    <td>{{ $b->borrow_date->format('M d, Y') }}</td>
                    <td class="{{ $b->is_overdue ? 'text-red-600 font-medium' : '' }}">{{ $b->due_date->format('M d, Y') }}</td>
                    <td><span class="badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                    <td><a href="{{ route('borrowings.show', $b) }}" class="text-xs text-blue-600 hover:underline">View</a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        {{-- Availability --}}
        <div class="card p-5">
            <h3 class="font-display text-sm text-gray-700 mb-3">Availability</h3>
            <div class="relative pt-1">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500">{{ $book->available_copies }} of {{ $book->total_copies }} available</span>
                </div>
                <div class="overflow-hidden h-2 rounded-full bg-gray-100">
                    <div class="h-2 rounded-full {{ $book->available_copies > 0 ? 'bg-emerald-500' : 'bg-red-400' }}"
                         style="width: {{ $book->total_copies > 0 ? ($book->available_copies / $book->total_copies) * 100 : 0 }}%"></div>
                </div>
            </div>
            @if($book->is_available)
                <a href="{{ route('borrowings.create', ['book_id' => $book->id]) }}" class="btn btn-primary w-full justify-center mt-4">Issue This Book</a>
            @else
                <button disabled class="btn btn-outline w-full justify-center mt-4 opacity-50 cursor-not-allowed">Not Available</button>
            @endif
        </div>

        {{-- Quick Stats --}}
        <div class="card p-5">
            <h3 class="font-display text-sm text-gray-700 mb-3">Statistics</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Total Borrowed</span><strong>{{ $book->borrowings->count() }}</strong></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Currently Out</span><strong>{{ $activeBorrowings->count() }}</strong></div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card p-5">
            <h3 class="font-display text-sm text-gray-700 mb-3">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('books.edit', $book) }}" class="btn btn-outline w-full justify-center">Edit Book</a>
                @if($book->activeBorrowings->isEmpty())
                <form method="POST" action="{{ route('books.destroy', $book) }}"
                      onsubmit="return confirm('Delete this book?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full justify-center">Delete Book</button>
                </form>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
