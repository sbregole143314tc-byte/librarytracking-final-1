@extends('layouts.user')
@section('title','Overdue Books')

@section('content')
<h1 style="font-family:'Playfair Display',serif;font-size:26px;color:#111827;margin:0 0 20px;font-weight:700;">Overdue Books</h1>

@if($overdueBorrowings->isEmpty())
<div class="card" style="padding:70px 24px;text-align:center;">
    <div style="width:64px;height:64px;border-radius:16px;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
        <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#15803d" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <p style="font-family:'Playfair Display',serif;font-size:20px;color:#111827;margin:0 0 6px;font-weight:600;">You're all caught up!</p>
    <p style="font-size:13.5px;color:#6b7280;margin:0;">No overdue books. Keep returning on time to avoid fines.</p>
    <a href="{{ route('user.books') }}" class="btn btn-primary" style="display:inline-flex;margin-top:18px;">View My Books</a>
</div>
@else

{{-- Alert header --}}
<div style="background:#dc2626;border-radius:14px;padding:22px 24px;margin-bottom:24px;color:#fff;">
    <div style="display:flex;align-items:center;gap:14px;">
        <div style="width:48px;height:48px;border-radius:12px;background:rgba(0,0,0,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="26" height="26" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        </div>
        <div>
            <p style="font-weight:700;font-size:17px;margin:0 0 4px;">Action Required — Return Overdue Books</p>
            <p style="font-size:13px;color:rgba(255,255,255,0.85);margin:0;">{{ $overdueBorrowings->count() }} overdue {{ Str::plural('book',$overdueBorrowings->count()) }} · Total fines: <strong>₱{{ number_format($overdueBorrowings->sum(fn($b)=>$b->calculateFine()),2) }}</strong></p>
        </div>
    </div>
    <div style="margin-top:14px;padding:14px;background:rgba(0,0,0,0.15);border-radius:10px;font-size:13px;">
        <p style="font-weight:600;margin:0 0 6px;">What to do:</p>
        <ul style="margin:0;padding-left:18px;color:rgba(255,255,255,0.9);line-height:1.8;">
            <li>Bring overdue books to the library counter</li>
            <li>Pay outstanding fines at the time of return</li>
            <li>Fine rate: <strong>₱5.00 per day</strong> per book</li>
            <li>You cannot borrow new books while fines are unpaid</li>
        </ul>
    </div>
</div>

<div style="display:flex;flex-direction:column;gap:14px;">
@foreach($overdueBorrowings as $b)
@php $fine = $b->calculateFine(); @endphp
<div class="card" style="overflow:hidden;border-color:#fca5a5;">
    <div style="height:4px;background:#dc2626;"></div>
    <div style="padding:20px 22px;display:flex;gap:16px;align-items:flex-start;">
        <div style="width:56px;height:72px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
            @if($b->book->cover_image)
                <img src="{{ asset('storage/'.$b->book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#fca5a5" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            @endif
        </div>
        <div style="flex:1;min-width:0;">
            <p style="font-size:15px;font-weight:600;color:#111827;margin:0;">{{ $b->book->title }}</p>
            <p style="font-size:13px;color:#6b7280;margin:2px 0 0;">{{ $b->book->author }}</p>
            <p style="font-size:11.5px;color:#9ca3af;margin:4px 0 0;font-family:monospace;">{{ $b->transaction_code }}</p>
            <div style="display:flex;gap:20px;margin-top:10px;">
                <div><p style="font-size:11px;color:#9ca3af;margin:0;">Borrowed</p><p style="font-size:13px;font-weight:500;color:#374151;margin:2px 0 0;">{{ $b->borrow_date->format('M d, Y') }}</p></div>
                <div><p style="font-size:11px;color:#9ca3af;margin:0;">Was Due</p><p style="font-size:13px;font-weight:700;color:#dc2626;margin:2px 0 0;">{{ $b->due_date->format('M d, Y') }}</p></div>
                <div><p style="font-size:11px;color:#9ca3af;margin:0;">Days Late</p><p style="font-size:13px;font-weight:700;color:#dc2626;margin:2px 0 0;">{{ $b->days_overdue }} days</p></div>
            </div>
        </div>
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px 18px;text-align:center;flex-shrink:0;">
            <p style="font-size:11px;color:#ef4444;font-weight:500;margin:0;">Current Fine</p>
            <p style="font-size:22px;font-weight:700;color:#dc2626;margin:4px 0;">₱{{ number_format($fine,2) }}</p>
            <p style="font-size:11px;color:#ef4444;margin:0;">+₱5.00/day</p>
        </div>
    </div>
    <div style="margin:0 22px 18px;padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;font-size:13px;color:#991b1b;display:flex;gap:10px;align-items:flex-start;">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0;margin-top:1px;"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <p style="margin:0;"><strong>Immediate return required.</strong> This book is {{ $b->days_overdue }} day(s) past its due date. Bring it to the library counter as soon as possible to stop the fine from growing.</p>
    </div>
</div>
@endforeach
</div>

<div style="margin-top:20px;padding:14px 18px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;text-align:center;font-size:13.5px;color:#1e40af;">
    📍 Return books at the <strong>Library Counter</strong> · Present your Member ID: <strong style="font-family:monospace;">{{ auth()->user()->member?->member_id }}</strong>
</div>
@endif
@endsection
