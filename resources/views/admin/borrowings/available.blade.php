@extends('layouts.admin')
@section('title','Available Books')
@section('page-title','Available to Borrow')
@section('breadcrumb','Books with copies ready for lending')

@section('content')
<div class="card mb-4 p-4">
    <form method="GET" class="flex gap-3 items-end flex-wrap">
        <div class="flex-1 min-w-[180px]"><label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, author, ISBN..." class="form-input">
        </div>
        <div class="w-40"><label class="form-label">Category</label>
            <select name="category" class="form-input">
                <option value="">All Categories</option>
                @foreach($categories as $cat)<option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>{{ $cat }}</option>@endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('borrowings.available') }}" class="btn btn-outline btn-sm">Clear</a>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="px-5 py-3 bg-emerald-50 border-b border-emerald-100">
        <p class="text-sm font-semibold text-emerald-800">{{ $books->total() }} books available for borrowing</p>
    </div>
    <table class="w-full data-table">
        <thead><tr><th>Book</th><th>Author</th><th>Category</th><th>Location</th><th>Available</th><th>Total</th><th></th></tr></thead>
        <tbody>
        @forelse($books as $book)
        <tr>
            <td>
                <a href="{{ route('books.show', $book) }}" class="font-medium text-gray-800 hover:text-blue-700 text-sm">{{ $book->title }}</a>
                @if($book->published_year)<p class="text-xs text-gray-400">{{ $book->published_year }}</p>@endif
            </td>
            <td class="text-sm text-gray-600">{{ $book->author }}</td>
            <td><span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">{{ $book->category }}</span></td>
            <td class="text-xs text-gray-500">{{ $book->location ?? '—' }}</td>
            <td><span class="text-emerald-700 font-bold text-sm">{{ $book->available_copies }}</span></td>
            <td class="text-gray-500 text-sm">{{ $book->total_copies }}</td>
            <td>
                <a href="{{ route('borrowings.create', ['book_id'=>$book->id]) }}" class="btn btn-primary btn-sm">Issue</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-gray-400 py-10">No books currently available.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $books->links() }}</div>
@endsection
