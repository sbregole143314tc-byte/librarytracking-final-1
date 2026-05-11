@extends('layouts.user')
@section('title','My Borrowed Books')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;color:#111827;margin:0;font-weight:700;">My Borrowed Books</h1>
    <span style="font-size:13.5px;color:#6b7280;">{{ $activeBorrowings->count() }} / 4 books</span>
</div>

{{-- Overdue banner --}}
@if($overdueBorrowings->isNotEmpty())
<div class="alert alert-danger" style="margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    <span><strong>{{ $overdueBorrowings->count() }} overdue {{ Str::plural('book',$overdueBorrowings->count()) }}</strong> — please return immediately to stop fine accumulation.</span>
    <a href="{{ route('user.overdue') }}" style="margin-left:auto;color:#991b1b;font-weight:600;text-decoration:underline;white-space:nowrap;">Details →</a>
</div>
@endif

{{-- Borrow limit bar --}}
<div class="card" style="padding:16px 20px;margin-bottom:20px;">
    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
        <span style="font-size:13.5px;font-weight:500;color:#374151;">Borrow Limit</span>
        <span style="font-size:13.5px;color:{{ $activeBorrowings->count()>=4?'#dc2626':'#6b7280' }};font-weight:{{ $activeBorrowings->count()>=4?'700':'400' }};">{{ $activeBorrowings->count() }} of 4 books used</span>
    </div>
    <div style="height:10px;background:#e5e7eb;border-radius:99px;overflow:hidden;">
        <div style="height:100%;border-radius:99px;background:{{ $activeBorrowings->count()>=4?'#dc2626':($activeBorrowings->count()>=3?'#f59e0b':'#1B3A5C') }};width:{{ ($activeBorrowings->count()/4)*100 }}%;"></div>
    </div>
    @if($activeBorrowings->count()>=4)
    <p style="font-size:12.5px;color:#dc2626;margin:6px 0 0;font-weight:500;">You've reached the 4-book limit. Return a book to borrow another.</p>
    @elseif($activeBorrowings->count()>0)
    <p style="font-size:12.5px;color:#6b7280;margin:6px 0 0;">You can borrow {{ 4-$activeBorrowings->count() }} more {{ Str::plural('book',4-$activeBorrowings->count()) }}.</p>
    @endif
</div>

@if($activeBorrowings->isEmpty())
<div class="card" style="padding:60px 24px;text-align:center;">
    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1" style="display:block;margin:0 auto 12px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    <p style="font-size:15px;font-weight:600;color:#6b7280;margin:0 0 4px;">No books currently borrowed</p>
    <p style="font-size:13.5px;color:#9ca3af;margin:0 0 16px;">Visit the library counter with your member ID to borrow books.</p>
    <a href="{{ route('user.available') }}" class="btn btn-primary">Browse Available Books</a>
</div>
@else
<div style="display:flex;flex-direction:column;gap:14px;">
    @foreach($activeBorrowings as $b)
    @php
        $isOverdue = $b->status==='overdue'||($b->status==='active'&&$b->due_date->isPast());
        $isDueSoon = !$isOverdue && $b->due_date->diffInDays(now(),false) >= -3;
        $fine = $b->calculateFine();
    @endphp
    <div class="card" style="overflow:hidden;border-color:{{ $isOverdue?'#fca5a5':($isDueSoon?'#fde68a':'#e8edf5') }}">
        @if($isOverdue)<div style="height:4px;background:#dc2626;"></div>@elseif($isDueSoon)<div style="height:4px;background:#f59e0b;"></div>@endif
        <div style="padding:18px 20px;display:flex;gap:16px;align-items:flex-start;">
            <div style="width:56px;height:72px;border-radius:8px;background:#e8edf5;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                @if($b->book->cover_image)
                    <img src="{{ asset('storage/'.$b->book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <p style="font-size:15px;font-weight:600;color:#111827;margin:0;">{{ $b->book->title }}</p>
                <p style="font-size:13px;color:#6b7280;margin:2px 0 0;">{{ $b->book->author }}</p>
                <p style="font-size:11.5px;color:#9ca3af;margin:4px 0 0;font-family:monospace;">{{ $b->transaction_code }}</p>
                <div style="display:flex;gap:20px;margin-top:10px;flex-wrap:wrap;">
                    <div><p style="font-size:11px;color:#9ca3af;margin:0;">Borrowed</p><p style="font-size:13px;font-weight:500;color:#374151;margin:2px 0 0;">{{ $b->borrow_date->format('M d, Y') }}</p></div>
                    <div><p style="font-size:11px;color:#9ca3af;margin:0;">Due Date</p><p style="font-size:13px;font-weight:600;color:{{ $isOverdue?'#dc2626':($isDueSoon?'#d97706':'#374151') }};margin:2px 0 0;">{{ $b->due_date->format('M d, Y') }}</p></div>
                    @if($b->renewals()->count()>0)<div><p style="font-size:11px;color:#9ca3af;margin:0;">Renewals</p><p style="font-size:13px;font-weight:500;color:#374151;margin:2px 0 0;">{{ $b->renewals()->count() }}/2</p></div>@endif
                </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                @if($isOverdue)
                    <span class="badge badge-overdue">{{ $b->days_overdue }}d OVERDUE</span>
                    @if($fine>0)<p style="font-size:15px;font-weight:700;color:#dc2626;margin:8px 0 0;">₱{{ number_format($fine,2) }}</p><p style="font-size:11px;color:#ef4444;margin:2px 0 0;">+₱5.00/day</p>@endif
                @elseif($isDueSoon)
                    <span class="badge badge-warning">Due in {{ $b->days_remaining }}d</span>
                @else
                    <span class="badge badge-active">Active</span>
                    <p style="font-size:12px;color:#6b7280;margin:6px 0 0;">{{ $b->days_remaining }} days left</p>
                @endif
            </div>
        </div>
        @if($isOverdue)
        <div style="margin:0 20px 16px;padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;font-size:13px;color:#991b1b;display:flex;gap:10px;align-items:flex-start;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0;margin-top:1px;"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <p style="margin:0;"><strong>Immediate return required.</strong> This book is {{ $b->days_overdue }} day(s) past due. Bring it to the library counter to stop the fine from increasing.</p>
        </div>
        @elseif($isDueSoon)
        <div style="margin:0 20px 16px;padding:12px 14px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:13px;color:#92400e;">
            ⏰ Due soon — please return by <strong>{{ $b->due_date->format('M d, Y') }}</strong> to avoid a ₱5.00/day late fine.
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif
@endsection
