@extends('layouts.app')

@section('title', 'Books')
@section('page-title', 'Book Catalog')
@section('breadcrumb', 'All books in the library')

@section('header-actions')
    <a href="{{ route('books.create') }}" class="btn btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Book
    </a>
@endsection

@section('content')

{{-- Filters --}}
<div class="card p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, author, ISBN..." class="form-input">
        </div>
        <div class="w-40">
            <label class="form-label">Category</label>
            <select name="category" class="form-input">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-36">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="damaged" {{ request('status') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        <div class="flex items-center gap-2 pb-0.5">
            <input type="checkbox" name="available" value="1" id="available" {{ request('available') ? 'checked' : '' }} class="rounded">
            <label for="available" class="text-sm text-gray-600">Available only</label>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('books.index') }}" class="btn btn-outline">Clear</a>
    </form>
</div>

{{-- Books Grid --}}
@if($books->isEmpty())
    <div class="card p-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        <p class="font-medium">No books found</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-5">
    @foreach($books as $book)
        <div class="card p-4 flex gap-4 hover:shadow-md transition-shadow">
            <div class="w-16 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 flex items-center justify-center">
                @if($book->cover_image)
                    <img src="{{ asset('storage/'.$book->cover_image) }}" class="w-full h-full object-cover" alt="{{ $book->title }}">
                @else
                    <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <a href="{{ route('books.show', $book) }}" class="font-semibold text-gray-900 hover:text-blue-700 text-sm leading-tight line-clamp-2">{{ $book->title }}</a>
                <p class="text-xs text-gray-500 mt-0.5">{{ $book->author }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">{{ $book->category }}</span>
                    @if($book->is_available)
                        <span class="badge-active">Available</span>
                    @else
                        <span class="badge-overdue">Unavailable</span>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mt-1.5">{{ $book->available_copies }}/{{ $book->total_copies }} copies</p>
            </div>
            <div class="flex flex-col gap-1 flex-shrink-0">
                <a href="{{ route('books.show', $book) }}" class="btn btn-outline text-xs py-1 px-2">View</a>
                <a href="{{ route('books.edit', $book) }}" class="btn btn-outline text-xs py-1 px-2">Edit</a>
                @if($book->is_available)
                <a href="{{ route('borrowings.create', ['book_id' => $book->id]) }}" class="btn btn-primary text-xs py-1 px-2">Issue</a>
                @endif
            </div>
        </div>
    @endforeach
    </div>
    {{ $books->links() }}
@endif

@endsection
