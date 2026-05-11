@extends('layouts.app')
@section('title','Edit Book')
@section('page-title','Edit Book')

@section('content')
<div class="max-w-2xl mx-auto">
<div class="card p-6">
    <form method="POST" enctype="multipart/form-data" action="{{ route('books.update', $book) }}">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="form-label">Title *</label>
                <input type="text" name="title" value="{{ old('title', $book->title) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Author *</label>
                <input type="text" name="author" value="{{ old('author', $book->author) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">ISBN *</label>
                <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Publisher</label>
                <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Published Year</label>
                <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}" min="1000" max="{{ date('Y')+1 }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Category *</label>
                <input type="text" name="category" value="{{ old('category', $book->category) }}" class="form-input" list="categories" required>
                <datalist id="categories">
                    @foreach($categories as $cat)<option value="{{ $cat }}">@endforeach
                </datalist>
            </div>
            <div>
                <label class="form-label">Total Copies *</label>
                <input type="number" name="total_copies" value="{{ old('total_copies', $book->total_copies) }}" min="1" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Price (₱)</label>
                <input type="number" name="price" value="{{ old('price', $book->price) }}" min="0" step="0.01" class="form-input">
            </div>
            <div>
                <label class="form-label">Shelf Location</label>
                <input type="text" name="location" value="{{ old('location', $book->location) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Status *</label>
                <select name="status" class="form-input" required>
                    @foreach(['active','damaged','lost','archived'] as $s)
                        <option value="{{ $s }}" {{ old('status', $book->status) == $s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-input">{{ old('description', $book->description) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Cover Image</label>
                @if($book->cover_image)
                    <img src="{{ asset('storage/'.$book->cover_image) }}" class="w-16 h-20 object-cover rounded-lg mb-2">
                @endif
                <input type="file" name="cover_image" accept="image/*" class="form-input">
            </div>
        </div>
        <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">
            <button type="submit" class="btn btn-primary">Update Book</button>
            <a href="{{ route('books.show', $book) }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection
