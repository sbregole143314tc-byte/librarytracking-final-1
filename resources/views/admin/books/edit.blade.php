@extends('layouts.admin')
@section('title','Edit Book')
@section('page-title','Edit Book')
@section('breadcrumb', $book->title)

@section('content')
<div style="max-width:680px;">
<div class="card" style="padding:28px 32px;">
<form method="POST" enctype="multipart/form-data" action="{{ route('books.update',$book) }}">
@csrf @method('PUT')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div style="grid-column:span 2;"><label class="form-label">Title *</label>
        <input type="text" name="title" value="{{ old('title',$book->title) }}" required class="form-input"></div>
    <div><label class="form-label">Author *</label>
        <input type="text" name="author" value="{{ old('author',$book->author) }}" required class="form-input"></div>
    <div><label class="form-label">ISBN *</label>
        <input type="text" name="isbn" value="{{ old('isbn',$book->isbn) }}" required class="form-input"></div>
    <div><label class="form-label">Publisher</label>
        <input type="text" name="publisher" value="{{ old('publisher',$book->publisher) }}" class="form-input"></div>
    <div><label class="form-label">Published Year</label>
        <input type="number" name="published_year" value="{{ old('published_year',$book->published_year) }}" min="1000" max="{{ date('Y')+1 }}" class="form-input"></div>
    <div><label class="form-label">Category *</label>
        <input type="text" name="category" value="{{ old('category',$book->category) }}" required class="form-input" list="cats">
        <datalist id="cats">@foreach($categories as $c)<option value="{{ $c }}">@endforeach</datalist></div>
    <div><label class="form-label">Total Copies *</label>
        <input type="number" name="total_copies" value="{{ old('total_copies',$book->total_copies) }}" min="1" required class="form-input"></div>
    <div><label class="form-label">Price (₱)</label>
        <input type="number" name="price" value="{{ old('price',$book->price) }}" min="0" step="0.01" class="form-input"></div>
    <div><label class="form-label">Shelf Location</label>
        <input type="text" name="location" value="{{ old('location',$book->location) }}" class="form-input"></div>
    <div><label class="form-label">Status *</label>
        <select name="status" class="form-input" required>
            @foreach(['active','damaged','lost','archived'] as $s)
            <option value="{{ $s }}" {{ old('status',$book->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
    <div style="grid-column:span 2;"><label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-input">{{ old('description',$book->description) }}</textarea></div>
    <div style="grid-column:span 2;"><label class="form-label">Cover Image</label>
        @if($book->cover_image)<img src="{{ asset('storage/'.$book->cover_image) }}" style="width:48px;height:60px;object-fit:cover;border-radius:6px;margin-bottom:8px;display:block;">@endif
        <input type="file" name="cover_image" accept="image/*" class="form-input" style="padding:8px 14px;"></div>
</div>
<div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid #f3f4f6;">
    <button type="submit" class="btn btn-primary">Update Book</button>
    <a href="{{ route('books.show',$book) }}" class="btn btn-outline">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
