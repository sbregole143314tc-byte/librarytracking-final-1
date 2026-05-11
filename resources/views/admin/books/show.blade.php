@extends('layouts.admin')
@section('title', $book->title)
@section('page-title', $book->title)
@section('breadcrumb', $book->author . ' · ' . $book->category)
@section('header-actions')
<a href="{{ route('books.edit',$book) }}" class="btn btn-outline btn-sm">Edit</a>
@if($book->is_available)<a href="{{ route('borrowings.create',['book_id'=>$book->id]) }}" class="btn btn-primary btn-sm">Issue Book</a>@endif
@endsection

@section('content')
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
<div style="display:flex;flex-direction:column;gap:18px;">
    {{-- Main info --}}
    <div class="card" style="padding:24px;display:flex;gap:20px;">
        <div style="width:90px;height:120px;border-radius:10px;background:#e8edf5;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
            @if($book->cover_image)<img src="{{ asset('storage/'.$book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
            @else<svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>@endif
        </div>
        <div>
            <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:#111827;margin:0 0 4px;font-weight:700;">{{ $book->title }}</h2>
            <p style="font-size:14px;color:#6b7280;margin:0 0 10px;">by {{ $book->author }}</p>
            @if($book->description)<p style="font-size:13.5px;color:#374151;line-height:1.6;margin:0;">{{ $book->description }}</p>@endif
        </div>
    </div>

    {{-- Details --}}
    <div class="card" style="padding:20px 24px;">
        <p style="font-family:'Playfair Display',serif;font-size:14px;color:#111827;margin:0 0 14px;font-weight:600;">Book Details</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            @foreach(['ISBN'=>$book->isbn,'Publisher'=>$book->publisher??'—','Year'=>$book->published_year??'—','Category'=>$book->category,'Location'=>$book->location??'—','Price'=>$book->price?'₱'.number_format($book->price,2):'—'] as $label=>$val)
            <div><p style="font-size:11px;color:#9ca3af;margin:0;text-transform:uppercase;letter-spacing:0.04em;">{{ $label }}</p>
                <p style="font-size:13.5px;font-weight:500;color:#374151;margin:3px 0 0;{{ $label==='ISBN'?'font-family:monospace;':'' }}">{{ $val }}</p></div>
            @endforeach
            <div><p style="font-size:11px;color:#9ca3af;margin:0;text-transform:uppercase;letter-spacing:0.04em;">Status</p>
                <span class="badge badge-{{ $book->status==='active'?'active':'returned' }}" style="margin-top:4px;display:inline-flex;">{{ ucfirst($book->status) }}</span></div>
        </div>
    </div>

    {{-- Active borrowings --}}
    @if($activeBorrowings->isNotEmpty())
    <div class="card" style="overflow:hidden;">
        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
            <p style="font-family:'Playfair Display',serif;font-size:14px;color:#111827;margin:0;font-weight:600;">Currently Borrowed</p>
        </div>
        <table class="data-table">
            <thead><tr><th>Member</th><th>Issued</th><th>Due</th><th>Status</th><th></th></tr></thead>
            <tbody>@foreach($activeBorrowings as $b)
            <tr>
                <td><a href="{{ route('members.show',$b->member) }}" style="color:#1d4ed8;text-decoration:none;font-size:13.5px;">{{ $b->member->name }}</a></td>
                <td style="font-size:13px;color:#6b7280;">{{ $b->borrow_date->format('M d, Y') }}</td>
                <td style="font-size:13px;color:{{ $b->is_overdue?'#dc2626':'' }};font-weight:{{ $b->is_overdue?'600':'' }};">{{ $b->due_date->format('M d, Y') }}</td>
                <td><span class="badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                <td><a href="{{ route('borrowings.show',$b) }}" style="font-size:12.5px;color:#1d4ed8;text-decoration:none;">View</a></td>
            </tr>@endforeach</tbody>
        </table>
    </div>
    @endif
</div>

{{-- Sidebar --}}
<div style="display:flex;flex-direction:column;gap:16px;">
    <div class="card" style="padding:20px;">
        <p style="font-family:'Playfair Display',serif;font-size:14px;color:#111827;margin:0 0 14px;font-weight:600;">Availability</p>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <span style="font-size:13px;color:#6b7280;">{{ $book->available_copies }} of {{ $book->total_copies }} available</span>
        </div>
        <div style="height:8px;background:#e5e7eb;border-radius:99px;overflow:hidden;margin-bottom:14px;">
            <div style="height:100%;background:{{ $book->available_copies>0?'#1B3A5C':'#dc2626' }};border-radius:99px;width:{{ $book->total_copies>0?($book->available_copies/$book->total_copies)*100:0 }}%;"></div>
        </div>
        @if($book->is_available)
        <a href="{{ route('borrowings.create',['book_id'=>$book->id]) }}" class="btn btn-primary" style="width:100%;justify-content:center;">Issue This Book</a>
        @else
        <button disabled style="width:100%;padding:9px;background:#f3f4f6;color:#9ca3af;border:none;border-radius:10px;font-size:14px;cursor:not-allowed;">Not Available</button>
        @endif
    </div>

    <div class="card" style="padding:20px;">
        <p style="font-family:'Playfair Display',serif;font-size:14px;color:#111827;margin:0 0 12px;font-weight:600;">Statistics</p>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <div style="display:flex;justify-content:space-between;font-size:13.5px;"><span style="color:#6b7280;">Total borrowed</span><strong>{{ $book->borrowings->count() }}</strong></div>
            <div style="display:flex;justify-content:space-between;font-size:13.5px;"><span style="color:#6b7280;">Currently out</span><strong>{{ $activeBorrowings->count() }}</strong></div>
        </div>
    </div>

    <div class="card" style="padding:20px;">
        <p style="font-family:'Playfair Display',serif;font-size:14px;color:#111827;margin:0 0 12px;font-weight:600;">Actions</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <a href="{{ route('books.edit',$book) }}" class="btn btn-outline" style="justify-content:center;">Edit Book</a>
            @if($book->activeBorrowings->isEmpty())
            <form method="POST" action="{{ route('books.destroy',$book) }}" onsubmit="return confirm('Delete this book?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">Delete Book</button>
            </form>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
