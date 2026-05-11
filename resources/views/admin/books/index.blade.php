@extends('layouts.admin')
@section('title','Books')
@section('page-title','Book Catalog')
@section('breadcrumb','Manage the library collection')
@section('header-actions')
<a href="{{ route('books.create') }}" class="btn btn-primary btn-sm">+ Add Book</a>
@endsection

@section('content')
<div class="card" style="padding:16px 20px;margin-bottom:18px;">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:180px;"><label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, author, ISBN..." class="form-input"></div>
        <div style="width:160px;"><label class="form-label">Category</label>
            <select name="category" class="form-input"><option value="">All</option>
            @foreach($categories as $c)<option value="{{ $c }}" {{ request('category')==$c?'selected':'' }}>{{ $c }}</option>@endforeach</select></div>
        <div style="width:130px;"><label class="form-label">Status</label>
            <select name="status" class="form-input"><option value="">All</option>
            @foreach(['active','damaged','archived'] as $s)<option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        <div style="display:flex;align-items:center;gap:6px;padding-bottom:2px;">
            <input type="checkbox" name="available" value="1" id="avail" {{ request('available')?'checked':'' }} style="width:15px;height:15px;">
            <label for="avail" style="font-size:13.5px;color:#374151;cursor:pointer;">Available only</label></div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('books.index') }}" class="btn btn-outline btn-sm">Clear</a>
    </form>
</div>

@if($books->isEmpty())
<div class="card" style="padding:60px;text-align:center;">
    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1" style="display:block;margin:0 auto 10px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    <p style="color:#9ca3af;font-size:14px;font-weight:500;margin:0;">No books found.</p>
</div>
@else
<div class="card" style="overflow:hidden;">
    <table class="data-table">
        <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>ISBN</th><th>Location</th><th>Copies</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @foreach($books as $book)
        <tr>
            <td><a href="{{ route('books.show',$book) }}" style="font-weight:600;color:#111827;text-decoration:none;font-size:13.5px;">{{ $book->title }}</a></td>
            <td style="color:#6b7280;font-size:13px;">{{ $book->author }}</td>
            <td><span style="font-size:11.5px;background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:20px;font-weight:500;">{{ $book->category }}</span></td>
            <td style="font-family:monospace;font-size:12px;color:#6b7280;">{{ $book->isbn }}</td>
            <td style="font-size:13px;color:#6b7280;">{{ $book->location ?? '—' }}</td>
            <td>
                <span style="font-weight:700;color:{{ $book->available_copies>0?'#15803d':'#dc2626' }};font-size:14px;">{{ $book->available_copies }}</span>
                <span style="color:#9ca3af;font-size:12px;">/{{ $book->total_copies }}</span>
            </td>
            <td><span class="badge badge-{{ $book->status==='active'?'active':'returned' }}">{{ ucfirst($book->status) }}</span></td>
            <td>
                <div style="display:flex;gap:10px;align-items:center;">
                    <a href="{{ route('books.show',$book) }}" style="font-size:12.5px;color:#1d4ed8;text-decoration:none;">View</a>
                    <a href="{{ route('books.edit',$book) }}" style="font-size:12.5px;color:#6b7280;text-decoration:none;">Edit</a>
                    @if($book->is_available)
                    <a href="{{ route('borrowings.create',['book_id'=>$book->id]) }}" style="font-size:12.5px;color:#15803d;text-decoration:none;">Issue</a>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div style="margin-top:14px;">{{ $books->links() }}</div>
@endif
@endsection
