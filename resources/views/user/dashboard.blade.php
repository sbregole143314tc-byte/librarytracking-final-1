@extends('layouts.user')
@section('title','Dashboard')

@section('content')

{{-- Overdue red alert --}}
@if($overdueBorrowings->isNotEmpty())
<div style="background:#fef2f2;border:2px solid #fca5a5;border-radius:14px;padding:20px 24px;margin-bottom:24px;">
    <div style="display:flex;align-items:flex-start;gap:14px;">
        <div style="width:40px;height:40px;border-radius:10px;background:#dc2626;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="20" height="20" fill="white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        </div>
        <div style="flex:1">
            <p style="font-weight:700;font-size:15px;color:#991b1b;margin:0 0 4px">⚠ Return Warning — {{ $overdueBorrowings->count() }} Overdue {{ Str::plural('Book',$overdueBorrowings->count()) }}</p>
            <p style="font-size:13.5px;color:#b91c1c;margin:0 0 12px">Please return these books to the library immediately. Fines are growing at ₱5.00 per day per book.</p>
            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach($overdueBorrowings as $b)
                <div style="background:#fff;border:1px solid #fca5a5;border-radius:10px;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;gap:16px;">
                    <div>
                        <p style="font-weight:600;font-size:14px;color:#111827;margin:0">{{ $b->book->title }}</p>
                        <p style="font-size:12px;color:#6b7280;margin:2px 0 0">Was due {{ $b->due_date->format('M d, Y') }}</p>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <p style="font-weight:700;font-size:14px;color:#dc2626;margin:0">{{ $b->days_overdue }} days late</p>
                        <p style="font-size:12px;color:#ef4444;margin:2px 0 0">₱{{ number_format($b->calculateFine(),2) }} fine</p>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('user.overdue') }}" class="btn btn-sm" style="margin-top:14px;background:#dc2626;color:#fff;display:inline-flex;">View All Overdue Books →</a>
        </div>
    </div>
</div>
@endif

{{-- Member status card --}}
<div class="card" style="padding:24px;margin-bottom:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:48px;height:48px;border-radius:12px;background:var(--brand);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;font-weight:700;font-family:'Playfair Display',serif;">
                {{ strtoupper(substr($member->name,0,1)) }}
            </div>
            <div>
                <p style="font-family:'Playfair Display',serif;font-size:18px;color:#111827;margin:0;font-weight:600;">{{ $member->name }}</p>
                <p style="font-size:12px;color:#6b7280;margin:2px 0 0;font-family:monospace;">{{ $member->member_id }} · {{ ucfirst($member->membership_type) }}</p>
            </div>
        </div>
        <div style="display:flex;gap:24px;text-align:center;">
            <div>
                <p style="font-size:26px;font-weight:700;color:#111827;margin:0;font-family:'Playfair Display',serif;">{{ $member->current_books_count }}</p>
                <p style="font-size:11.5px;color:#6b7280;margin:2px 0 0;">Borrowed</p>
            </div>
            <div>
                <p style="font-size:26px;font-weight:700;color:{{ $member->books_remaining > 0 ? '#15803d' : '#dc2626' }};margin:0;font-family:'Playfair Display',serif;">{{ $member->books_remaining }}</p>
                <p style="font-size:11.5px;color:#6b7280;margin:2px 0 0;">Can Borrow</p>
            </div>
            <div>
                <p style="font-size:26px;font-weight:700;color:{{ $overdueBorrowings->count()>0?'#dc2626':'#9ca3af' }};margin:0;font-family:'Playfair Display',serif;">{{ $overdueBorrowings->count() }}</p>
                <p style="font-size:11.5px;color:#6b7280;margin:2px 0 0;">Overdue</p>
            </div>
            @if($member->outstanding_fines > 0)
            <div>
                <p style="font-size:26px;font-weight:700;color:#dc2626;margin:0;font-family:'Playfair Display',serif;">₱{{ number_format($member->outstanding_fines,0) }}</p>
                <p style="font-size:11.5px;color:#6b7280;margin:2px 0 0;">Fines</p>
            </div>
            @endif
        </div>
    </div>
    {{-- Borrow limit bar --}}
    <div style="margin-top:18px;padding-top:18px;border-top:1px solid #f3f4f6;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
            <span style="font-size:12.5px;font-weight:500;color:#374151;">Borrow Limit</span>
            <span style="font-size:12.5px;color:#6b7280;">{{ $member->current_books_count }} / 4 books used</span>
        </div>
        <div style="height:8px;background:#e5e7eb;border-radius:99px;overflow:hidden;">
            <div style="height:100%;border-radius:99px;background:{{ $member->current_books_count>=4?'#dc2626':($member->current_books_count>=3?'#f59e0b':'#1B3A5C') }};width:{{ ($member->current_books_count/4)*100 }}%;transition:width 0.3s;"></div>
        </div>
        @if($member->current_books_count >= 4)
            <p style="font-size:12px;color:#dc2626;margin:5px 0 0;font-weight:500;">Limit reached — return a book to borrow another.</p>
        @endif
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    {{-- Currently Borrowed --}}
    <div class="card" style="grid-column:span 1;">
        <div style="padding:18px 20px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;">
            <p style="font-family:'Playfair Display',serif;font-size:16px;color:#111827;margin:0;font-weight:600;">Currently Borrowed</p>
            <a href="{{ route('user.books') }}" style="font-size:12.5px;color:#1d4ed8;text-decoration:none;font-weight:500;">View all →</a>
        </div>
        @if($activeBorrowings->isEmpty())
        <div style="padding:36px 20px;text-align:center;">
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1" style="display:block;margin:0 auto 10px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <p style="color:#9ca3af;font-size:13.5px;margin:0;">No books currently borrowed</p>
            <a href="{{ route('user.available') }}" style="display:inline-block;margin-top:10px;font-size:13px;color:#1d4ed8;text-decoration:none;">Browse available books →</a>
        </div>
        @else
        <div>
            @foreach($activeBorrowings as $b)
            @php $isOverdue = $b->status==='overdue'||($b->status==='active'&&$b->due_date->isPast()); @endphp
            <div style="padding:14px 20px;border-bottom:1px solid #f9fafb;display:flex;align-items:center;justify-content:space-between;gap:12px;background:{{ $isOverdue?'#fff8f8':'' }}">
                <div style="flex:1;min-width:0;">
                    <p style="font-size:13.5px;font-weight:500;color:#111827;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $b->book->title }}</p>
                    <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">{{ $b->book->author }}</p>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    @if($isOverdue)
                        <span style="font-size:12px;font-weight:700;color:#dc2626;">{{ $b->days_overdue }}d LATE</span>
                    @else
                        <span style="font-size:12px;color:{{ $b->days_remaining<=2?'#d97706':'#374151' }};font-weight:{{ $b->days_remaining<=2?'600':'400' }};">Due {{ $b->due_date->format('M d') }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Right column --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Due soon --}}
        @if($dueSoon->isNotEmpty())
        <div class="card" style="padding:18px 20px;">
            <p style="font-family:'Playfair Display',serif;font-size:15px;color:#92400e;margin:0 0 12px;font-weight:600;">⏰ Due Within 3 Days</p>
            @foreach($dueSoon as $b)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #fef9c3;last-child:border-0">
                <p style="font-size:13.5px;color:#111827;margin:0;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $b->book->title }}</p>
                <span style="font-size:12px;color:#d97706;font-weight:600;flex-shrink:0;margin-left:10px;">{{ $b->due_date->format('M d') }}</span>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Recent history --}}
        <div class="card" style="padding:18px 20px;">
            <p style="font-family:'Playfair Display',serif;font-size:15px;color:#111827;margin:0 0 12px;font-weight:600;">Recently Returned</p>
            @if($recentHistory->isEmpty())
                <p style="font-size:13.5px;color:#9ca3af;margin:0;">No history yet.</p>
            @else
                @foreach($recentHistory->take(4) as $b)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                    <p style="font-size:13.5px;color:#374151;margin:0;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $b->book->title }}</p>
                    <span style="font-size:12px;color:#9ca3af;flex-shrink:0;margin-left:10px;">{{ $b->return_date?->format('M d') }}</span>
                </div>
                @endforeach
                <a href="{{ route('user.history') }}" style="display:block;margin-top:10px;font-size:12.5px;color:#1d4ed8;text-decoration:none;font-weight:500;">Full history →</a>
            @endif
        </div>

        {{-- Browse CTA --}}
        <a href="{{ route('user.available') }}" class="card card-hover" style="padding:18px 20px;display:flex;align-items:center;gap:14px;text-decoration:none;">
            <div style="width:40px;height:40px;border-radius:10px;background:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <p style="font-weight:600;font-size:14px;color:#111827;margin:0;">Browse Available Books</p>
                <p style="font-size:12.5px;color:#6b7280;margin:2px 0 0;">Explore what's ready to borrow today</p>
            </div>
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="2" style="margin-left:auto;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>
@endsection
