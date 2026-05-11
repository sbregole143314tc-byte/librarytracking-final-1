@extends('layouts.admin')
@section('title', $borrowing->transaction_code)
@section('page-title','Transaction Details')
@section('breadcrumb', $borrowing->transaction_code)
@section('header-actions')
@if(in_array($borrowing->status,['active','overdue']))
<a href="{{ route('borrowings.return',$borrowing) }}" class="btn btn-primary btn-sm">Process Return</a>
@endif
@endsection

@section('content')
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">

{{-- Main --}}
<div style="display:flex;flex-direction:column;gap:18px;">

    <div class="card" style="padding:22px 24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
            <p style="font-family:'Playfair Display',serif;font-size:16px;color:#111827;margin:0;font-weight:600;">Transaction Info</p>
            <span class="badge badge-{{ $borrowing->status }}">{{ ucfirst($borrowing->status) }}</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Transaction Code</p><p style="font-size:13.5px;font-weight:600;color:#374151;margin:3px 0 0;font-family:monospace;">{{ $borrowing->transaction_code }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Issued By</p><p style="font-size:13.5px;color:#374151;margin:3px 0 0;">{{ $borrowing->issuedBy->name }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Borrow Date</p><p style="font-size:13.5px;color:#374151;margin:3px 0 0;">{{ $borrowing->borrow_date->format('M d, Y') }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Due Date</p><p style="font-size:13.5px;color:{{ $borrowing->is_overdue?'#dc2626':'' }};font-weight:{{ $borrowing->is_overdue?'600':'' }};margin:3px 0 0;">{{ $borrowing->due_date->format('M d, Y') }}</p></div>
            @if($borrowing->return_date)
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Return Date</p><p style="font-size:13.5px;color:#374151;margin:3px 0 0;">{{ $borrowing->return_date->format('M d, Y') }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Returned To</p><p style="font-size:13.5px;color:#374151;margin:3px 0 0;">{{ $borrowing->returnedTo?->name??'—' }}</p></div>
            @endif
            @if($borrowing->condition_on_return)
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Condition</p><p style="font-size:13.5px;color:#374151;margin:3px 0 0;text-transform:capitalize;">{{ $borrowing->condition_on_return }}</p></div>
            @endif
            @if($borrowing->notes)
            <div style="grid-column:span 2;"><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Notes</p><p style="font-size:13.5px;color:#374151;margin:3px 0 0;">{{ $borrowing->notes }}</p></div>
            @endif
        </div>
    </div>

    {{-- Fine --}}
    @if($borrowing->fine_amount>0||$borrowing->is_overdue)
    <div class="card" style="padding:20px 24px;border-color:#fecaca;">
        <p style="font-family:'Playfair Display',serif;font-size:15px;color:#991b1b;margin:0 0 14px;font-weight:600;">Fine Information</p>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Days Overdue</p><p style="font-size:20px;font-weight:700;color:#dc2626;margin:3px 0 0;">{{ $borrowing->days_overdue }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Fine Amount</p><p style="font-size:20px;font-weight:700;color:#dc2626;margin:3px 0 0;">₱{{ number_format($borrowing->fine_amount,2) }}</p></div>
            <div><p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;margin:0;">Fine Paid</p><p style="font-size:15px;font-weight:600;color:{{ $borrowing->fine_paid?'#15803d':'#dc2626' }};margin:3px 0 0;">{{ $borrowing->fine_paid?'Yes':'No' }}</p></div>
        </div>
        @if($borrowing->fine_amount>0&&!$borrowing->fine_paid&&$borrowing->status==='returned')
        <form method="POST" action="{{ route('borrowings.pay-fine',$borrowing) }}" style="margin-top:16px;">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">Mark Fine as Paid</button>
        </form>
        @endif
    </div>
    @endif

    {{-- Renewals --}}
    @if($borrowing->renewals->isNotEmpty())
    <div class="card" style="overflow:hidden;">
        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
            <p style="font-family:'Playfair Display',serif;font-size:14px;color:#111827;margin:0;font-weight:600;">Renewal History</p>
        </div>
        <table class="data-table">
            <thead><tr><th>#</th><th>Previous Due</th><th>New Due</th><th>Renewed By</th><th>Date</th></tr></thead>
            <tbody>@foreach($borrowing->renewals as $i=>$r)
            <tr>
                <td style="color:#9ca3af;">{{ $i+1 }}</td>
                <td style="font-size:13px;">{{ $r->previous_due_date->format('M d, Y') }}</td>
                <td style="font-size:13px;font-weight:600;">{{ $r->new_due_date->format('M d, Y') }}</td>
                <td style="font-size:13px;">{{ $r->renewedBy->name }}</td>
                <td style="font-size:13px;color:#6b7280;">{{ $r->created_at->format('M d, Y') }}</td>
            </tr>@endforeach</tbody>
        </table>
    </div>
    @endif
</div>

{{-- Sidebar --}}
<div style="display:flex;flex-direction:column;gap:16px;">
    <div class="card" style="padding:18px 20px;">
        <p style="font-family:'Playfair Display',serif;font-size:13px;color:#111827;margin:0 0 12px;font-weight:600;">Book</p>
        <a href="{{ route('books.show',$borrowing->book) }}" style="text-decoration:none;">
            <p style="font-size:14px;font-weight:600;color:#111827;margin:0;line-height:1.4;">{{ $borrowing->book->title }}</p>
            <p style="font-size:13px;color:#6b7280;margin:3px 0 0;">{{ $borrowing->book->author }}</p>
            <p style="font-size:11.5px;color:#9ca3af;margin:4px 0 0;font-family:monospace;">ISBN: {{ $borrowing->book->isbn }}</p>
        </a>
    </div>

    <div class="card" style="padding:18px 20px;">
        <p style="font-family:'Playfair Display',serif;font-size:13px;color:#111827;margin:0 0 12px;font-weight:600;">Member</p>
        <a href="{{ route('members.show',$borrowing->member) }}" style="text-decoration:none;">
            <p style="font-size:14px;font-weight:600;color:#111827;margin:0;">{{ $borrowing->member->name }}</p>
            <p style="font-size:12px;color:#9ca3af;margin:2px 0 0;font-family:monospace;">{{ $borrowing->member->member_id }}</p>
            <p style="font-size:13px;color:#6b7280;margin:3px 0 0;">{{ $borrowing->member->email }}</p>
        </a>
    </div>

    @if(in_array($borrowing->status,['active','overdue']))
    <div class="card" style="padding:18px 20px;display:flex;flex-direction:column;gap:10px;">
        <a href="{{ route('borrowings.return',$borrowing) }}" class="btn btn-primary" style="justify-content:center;">Process Return</a>
        @if($borrowing->canRenew())
        <form method="POST" action="{{ route('borrowings.renew',$borrowing) }}">
            @csrf
            <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center;">Renew Loan</button>
        </form>
        @else
        <p style="text-align:center;font-size:12.5px;color:#9ca3af;">Cannot renew (limit reached or overdue)</p>
        @endif
    </div>
    @endif
</div>
</div>
@endsection
