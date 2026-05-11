@extends('layouts.admin')
@section('title','Add Book')
@section('page-title','Add New Book')

@section('content')
<div style="max-width:680px;">
<div class="card" style="padding:28px 32px;">
<form method="POST" enctype="multipart/form-data" action="{{ route('books.store') }}">
@csrf
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div style="grid-column:span 2;"><label class="form-label">Title <span style="color:#ef4444">*</span></label>
        <input type="text" name="title" value="{{ old('title') }}" required class="form-input"></div>
    <div><label class="form-label">Author <span style="color:#ef4444">*</span></label>
        <input type="text" name="author" value="{{ old('author') }}" required class="form-input"></div>
    <div><label class="form-label">ISBN <span style="color:#ef4444">*</span></label>
        <input type="text" name="isbn" value="{{ old('isbn') }}" required class="form-input"></div>
    <div><label class="form-label">Publisher</label>
        <input type="text" name="publisher" value="{{ old('publisher') }}" class="form-input"></div>
    <div><label class="form-label">Published Year</label>
        <input type="number" name="published_year" value="{{ old('published_year') }}" min="1000" max="{{ date('Y')+1 }}" class="form-input"></div>
    <div><label class="form-label">Category <span style="color:#ef4444">*</span></label>
        <input type="text" name="category" value="{{ old('category') }}" required class="form-input" list="categories">
        <datalist id="categories">@foreach($categories as $c)<option value="{{ $c }}">@endforeach
            <option value="Fiction"><option value="Non-Fiction"><option value="Technology"><option value="Science">
            <option value="History"><option value="Philosophy"><option value="Psychology"><option value="Fantasy">
            <option value="Sci-Fi"><option value="Self-Help"><option value="Biography"><option value="Reference">
        </datalist></div>
    <div><label class="form-label">Total Copies <span style="color:#ef4444">*</span></label>
        <input type="number" name="total_copies" value="{{ old('total_copies',1) }}" min="1" required class="form-input"></div>
    <div><label class="form-label">Price (₱)</label>
        <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" class="form-input"></div>
    <div><label class="form-label">Shelf Location</label>
        <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g. Shelf A-1" class="form-input"></div>
    <div><label class="form-label">Status <span style="color:#ef4444">*</span></label>
        <select name="status" class="form-input" required>
            @foreach(['active','damaged','lost','archived'] as $s)
            <option value="{{ $s }}" {{ old('status','active')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
    <div style="grid-column:span 2;"><label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea></div>
    <div style="grid-column:span 2;"><label class="form-label">Cover Image</label>
        <input type="file" name="cover_image" accept="image/*" class="form-input" style="padding:8px 14px;"></div>
</div>
<div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid #f3f4f6;">
    <button type="submit" class="btn btn-primary">Add Book</button>
    <a href="{{ route('books.index') }}" class="btn btn-outline">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
