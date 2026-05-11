@extends('layouts.user')
@section('title','Browse Books')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;color:#111827;margin:0;font-weight:700;">Available Books</h1>
    <span style="font-size:13.5px;color:#6b7280;">{{ $books->total() }} books available</span>
</div>

{{-- Status notice --}}
@if(!$member->can_borrow)
<div class="alert {{ $member->current_books_count>=4?'alert-danger':'alert-warning' }}" style="margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    <div>
        <strong>You cannot borrow right now.</strong>
        @if($member->current_books_count>=4) You've reached the 4-book limit. Return a book first.
        @elseif($member->outstanding_fines>0) You have ₱{{ number_format($member->outstanding_fines,2) }} in outstanding fines. Settle them at the library.
        @elseif(!$member->is_active) Your membership is {{ $member->status }}. Contact the library.
        @endif
    </div>
</div>
@else
<div class="alert alert-info" style="margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
    You can borrow <strong>{{ $member->books_remaining }}</strong> more {{ Str::plural('book',$member->books_remaining) }}. Visit the library counter with the title or ISBN.
</div>
@endif

{{-- Search & filter --}}
<div class="card" style="padding:16px 20px;margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:180px;">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, author, ISBN..." class="form-input">
        </div>
        <div style="width:180px;">
            <label class="form-label">Category</label>
            <select name="category" class="form-input">
                <option value="">All Categories</option>
                @foreach($categories as $cat)<option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>{{ $cat }}</option>@endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request()->hasAny(['search','category']))<a href="{{ route('user.available') }}" class="btn btn-outline">Clear</a>@endif
    </form>
</div>

{{-- Books grid --}}
@if($books->isEmpty())
<div class="card" style="padding:50px 24px;text-align:center;">
    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1" style="display:block;margin:0 auto 10px"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    <p style="font-size:14px;font-weight:600;color:#6b7280;margin:0;">No books found.</p>
    <a href="{{ route('user.available') }}" style="font-size:13px;color:#1d4ed8;text-decoration:none;display:inline-block;margin-top:6px;">Clear search</a>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-bottom:20px;">
    @foreach($books as $book)
    <div class="card card-hover" style="padding:16px;display:flex;flex-direction:column;gap:12px;">
        <div style="width:100%;height:120px;background:#e8edf5;border-radius:8px;display:flex;align-items:center;justify-content:center;overflow:hidden;">
            @if($book->cover_image)
                <img src="{{ asset('storage/'.$book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            @endif
        </div>
        <div style="flex:1;">
            <p style="font-size:14px;font-weight:600;color:#111827;margin:0;line-height:1.4;">{{ $book->title }}</p>
            <p style="font-size:12.5px;color:#6b7280;margin:3px 0 0;">{{ $book->author }}</p>
            @if($book->published_year)<p style="font-size:11.5px;color:#9ca3af;margin:2px 0 0;">{{ $book->published_year }}</p>@endif
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:11.5px;background:#dbeafe;color:#1e40af;padding:3px 8px;border-radius:20px;font-weight:500;">{{ $book->category }}</span>
            <span style="font-size:12px;color:#15803d;font-weight:600;">{{ $book->available_copies }} avail.</span>
        </div>
        @if($book->location)<p style="font-size:11.5px;color:#9ca3af;margin:0;">📍 {{ $book->location }}</p>@endif
    </div>
    @endforeach
</div>
{{ $books->withQueryString()->links() }}
@endif

<div style="margin-top:16px;padding:14px 18px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;text-align:center;font-size:13.5px;color:#1e40af;">
    To borrow, visit the <strong>Library Counter</strong> · Member ID: <strong style="font-family:monospace;">{{ $member->member_id }}</strong>
</div>
@endsection
