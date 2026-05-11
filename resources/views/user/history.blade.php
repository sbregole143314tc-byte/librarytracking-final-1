@extends('layouts.user')
@section('title','Borrowing History')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <h1 style="font-family:'Playfair Display',serif;font-size:26px;color:#111827;margin:0;font-weight:700;">Borrowing History</h1>
    <span style="font-size:13.5px;color:#6b7280;">{{ $history->total() }} returned books</span>
</div>

@if($history->isEmpty())
<div class="card" style="padding:60px 24px;text-align:center;">
    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1" style="display:block;margin:0 auto 12px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p style="font-size:14px;font-weight:600;color:#6b7280;margin:0 0 4px;">No borrowing history yet</p>
    <p style="font-size:13px;color:#9ca3af;margin:0;">Books you return will appear here.</p>
</div>
@else
<div class="card" style="overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;">
                <th style="text-align:left;padding:12px 20px;font-size:11.5px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e8edf5;">Book</th>
                <th style="text-align:left;padding:12px 20px;font-size:11.5px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e8edf5;">Borrowed</th>
                <th style="text-align:left;padding:12px 20px;font-size:11.5px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e8edf5;">Returned</th>
                <th style="text-align:left;padding:12px 20px;font-size:11.5px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e8edf5;">Fine</th>
            </tr>
        </thead>
        <tbody>
        @foreach($history as $b)
        <tr style="border-bottom:1px solid #f3f4f6;">
            <td style="padding:13px 20px;">
                <p style="font-size:14px;font-weight:500;color:#111827;margin:0;">{{ $b->book->title }}</p>
                <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">{{ $b->book->author }}</p>
            </td>
            <td style="padding:13px 20px;font-size:13.5px;color:#374151;">{{ $b->borrow_date->format('M d, Y') }}</td>
            <td style="padding:13px 20px;font-size:13.5px;color:#374151;">{{ $b->return_date?->format('M d, Y') ?? '—' }}</td>
            <td style="padding:13px 20px;">
                @if($b->fine_amount>0)
                    <span style="font-size:13.5px;font-weight:600;color:{{ $b->fine_paid?'#6b7280':'#dc2626' }};{{ $b->fine_paid?'text-decoration:line-through;':'' }}">₱{{ number_format($b->fine_amount,2) }}</span>
                    @if($b->fine_paid)<span style="font-size:11.5px;color:#15803d;font-weight:500;margin-left:4px;">paid</span>@endif
                @else
                    <span style="font-size:13.5px;color:#9ca3af;">—</span>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div style="margin-top:16px;">{{ $history->links() }}</div>
@endif
@endsection
