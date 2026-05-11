@extends('layouts.admin')
@section('title', $member->name)
@section('page-title', $member->name)
@section('breadcrumb', $member->member_id)
@section('header-actions')
<a href="{{ route('members.edit',$member) }}" class="btn btn-outline btn-sm">Edit</a>
@if($member->is_active)<a href="{{ route('borrowings.create',['member_id'=>$member->id]) }}" class="btn btn-primary btn-sm">Issue Book</a>@endif
@endsection

@section('content')
<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;">

{{-- Profile sidebar --}}
<div style="display:flex;flex-direction:column;gap:16px;">
    <div class="card" style="padding:24px;text-align:center;">
        <div style="width:64px;height:64px;border-radius:50%;background:#1B3A5C;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:700;margin:0 auto 12px;font-family:'Playfair Display',serif;">
            {{ strtoupper(substr($member->name,0,1)) }}
        </div>
        <h2 style="font-family:'Playfair Display',serif;font-size:17px;color:#111827;margin:0 0 4px;font-weight:700;">{{ $member->name }}</h2>
        <p style="font-size:12px;color:#9ca3af;margin:0 0 10px;font-family:monospace;">{{ $member->member_id }}</p>
        <div style="display:flex;justify-content:center;gap:6px;flex-wrap:wrap;">
            <span class="badge badge-{{ $member->status==='active'?'active':'overdue' }}">{{ ucfirst($member->status) }}</span>
            <span style="font-size:11.5px;background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:20px;font-weight:500;text-transform:capitalize;">{{ $member->membership_type }}</span>
        </div>
        @if($member->outstanding_fines>0)
        <div style="margin-top:12px;padding:10px;background:#fef2f2;border-radius:8px;font-size:13px;color:#991b1b;font-weight:600;">
            Fines: ₱{{ number_format($member->outstanding_fines,2) }}
        </div>
        @endif
    </div>

    <div class="card" style="padding:18px 20px;">
        <p style="font-family:'Playfair Display',serif;font-size:13px;color:#111827;margin:0 0 12px;font-weight:600;">Contact</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <div><p style="font-size:11px;color:#9ca3af;margin:0;text-transform:uppercase;letter-spacing:0.04em;">Email</p><p style="font-size:13px;color:#374151;margin:2px 0 0;">{{ $member->email }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;margin:0;text-transform:uppercase;letter-spacing:0.04em;">Phone</p><p style="font-size:13px;color:#374151;margin:2px 0 0;">{{ $member->phone??'—' }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;margin:0;text-transform:uppercase;letter-spacing:0.04em;">Address</p><p style="font-size:12.5px;color:#374151;margin:2px 0 0;">{{ $member->address??'—' }}</p></div>
        </div>
    </div>

    <div class="card" style="padding:18px 20px;">
        <p style="font-family:'Playfair Display',serif;font-size:13px;color:#111827;margin:0 0 12px;font-weight:600;">Membership</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <div><p style="font-size:11px;color:#9ca3af;margin:0;text-transform:uppercase;letter-spacing:0.04em;">Start</p><p style="font-size:13px;color:#374151;margin:2px 0 0;">{{ $member->membership_start->format('M d, Y') }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;margin:0;text-transform:uppercase;letter-spacing:0.04em;">Expiry</p><p style="font-size:13px;color:{{ $member->is_expired?'#dc2626':'' }};font-weight:{{ $member->is_expired?'600':'' }};margin:2px 0 0;">{{ $member->membership_expiry->format('M d, Y') }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;margin:0;text-transform:uppercase;letter-spacing:0.04em;">Book Limit</p><p style="font-size:13px;color:#374151;margin:2px 0 0;">{{ $member->current_books_count }} / {{ $member->max_books_allowed }} books</p></div>
        </div>
        <form method="POST" action="{{ route('members.renew',$member) }}" style="margin-top:14px;">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;">Renew +1 Year</button>
        </form>
    </div>
</div>

{{-- Main content --}}
<div style="display:flex;flex-direction:column;gap:16px;">
    @if($activeBorrowings->isNotEmpty())
    <div class="card" style="overflow:hidden;">
        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
            <p style="font-family:'Playfair Display',serif;font-size:14px;color:#111827;margin:0;font-weight:600;">Currently Borrowed ({{ $activeBorrowings->count() }})</p>
        </div>
        <table class="data-table">
            <thead><tr><th>Book</th><th>Issued</th><th>Due</th><th>Status</th><th></th></tr></thead>
            <tbody>@foreach($activeBorrowings as $b)
            <tr>
                <td><a href="{{ route('books.show',$b->book) }}" style="font-weight:500;color:#111827;text-decoration:none;font-size:13.5px;">{{ $b->book->title }}</a><p style="font-size:12px;color:#9ca3af;margin:1px 0 0;">{{ $b->book->author }}</p></td>
                <td style="font-size:13px;color:#6b7280;">{{ $b->borrow_date->format('M d, Y') }}</td>
                <td style="font-size:13px;color:{{ $b->is_overdue?'#dc2626':'' }};font-weight:{{ $b->is_overdue?'600':'' }};">{{ $b->due_date->format('M d, Y') }}@if($b->is_overdue)<br><span style="font-size:11.5px;font-weight:400;color:#ef4444;">{{ $b->days_overdue }}d late</span>@endif</td>
                <td><span class="badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                <td><div style="display:flex;gap:10px;"><a href="{{ route('borrowings.return',$b) }}" style="font-size:12.5px;color:#15803d;text-decoration:none;">Return</a><a href="{{ route('borrowings.show',$b) }}" style="font-size:12.5px;color:#1d4ed8;text-decoration:none;">View</a></div></td>
            </tr>@endforeach</tbody>
        </table>
    </div>
    @endif

    <div class="card" style="overflow:hidden;">
        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
            <p style="font-family:'Playfair Display',serif;font-size:14px;color:#111827;margin:0;font-weight:600;">Borrowing History</p>
        </div>
        @if($history->isEmpty())
        <p style="text-align:center;color:#9ca3af;font-size:13.5px;padding:32px;">No borrowing history yet.</p>
        @else
        <table class="data-table">
            <thead><tr><th>Book</th><th>Borrowed</th><th>Returned</th><th>Fine</th></tr></thead>
            <tbody>@foreach($history as $b)
            <tr>
                <td style="font-size:13.5px;color:#374151;">{{ $b->book->title }}</td>
                <td style="font-size:13px;color:#6b7280;">{{ $b->borrow_date->format('M d, Y') }}</td>
                <td style="font-size:13px;color:#6b7280;">{{ $b->return_date?->format('M d, Y')??'—' }}</td>
                <td style="font-size:13px;font-weight:{{ $b->fine_amount>0?'600':'' }};color:{{ $b->fine_amount>0?'#dc2626':'#9ca3af' }};">
                    {{ $b->fine_amount>0?'₱'.number_format($b->fine_amount,2):'—' }}
                    @if($b->fine_paid)<span style="font-size:11.5px;color:#15803d;font-weight:500;margin-left:4px;">paid</span>@endif
                </td>
            </tr>@endforeach</tbody>
        </table>
        @endif
    </div>
</div>
</div>
@endsection
