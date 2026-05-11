@extends('layouts.admin')
@section('title','Return Book')
@section('page-title','Process Book Return')

@section('content')
<div style="max-width:560px;">
<div class="card" style="padding:28px 32px;">

{{-- Book & member summary --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:16px;background:#f8fafc;border-radius:12px;margin-bottom:24px;">
    <div>
        <p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin:0;">Book</p>
        <p style="font-size:14px;font-weight:600;color:#111827;margin:4px 0 0;">{{ $borrowing->book->title }}</p>
        <p style="font-size:12.5px;color:#6b7280;margin:2px 0 0;">{{ $borrowing->book->author }}</p>
    </div>
    <div>
        <p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin:0;">Member</p>
        <p style="font-size:14px;font-weight:600;color:#111827;margin:4px 0 0;">{{ $borrowing->member->name }}</p>
        <p style="font-size:12px;color:#9ca3af;margin:2px 0 0;font-family:monospace;">{{ $borrowing->member->member_id }}</p>
    </div>
</div>

{{-- Dates --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
    <div style="padding:14px;background:#f8fafc;border-radius:10px;text-align:center;">
        <p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin:0;">Borrowed On</p>
        <p style="font-size:15px;font-weight:600;color:#374151;margin:5px 0 0;">{{ $borrowing->borrow_date->format('M d, Y') }}</p>
    </div>
    <div style="padding:14px;background:{{ $borrowing->is_overdue?'#fef2f2':'#f8fafc' }};border-radius:10px;text-align:center;{{ $borrowing->is_overdue?'border:1px solid #fecaca;':'' }}">
        <p style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin:0;">Due Date</p>
        <p style="font-size:15px;font-weight:600;color:{{ $borrowing->is_overdue?'#dc2626':'#374151' }};margin:5px 0 0;">{{ $borrowing->due_date->format('M d, Y') }}</p>
    </div>
</div>

{{-- Fine alert --}}
@if($fine > 0)
<div style="padding:14px 16px;background:#fef2f2;border:1px solid #fecaca;border-radius:12px;margin-bottom:20px;">
    <p style="font-size:14px;font-weight:700;color:#dc2626;margin:0;">⚠ Overdue Fine: ₱{{ number_format($fine,2) }}</p>
    <p style="font-size:12.5px;color:#ef4444;margin:4px 0 0;">{{ $borrowing->days_overdue }} days × ₱5.00/day — will be added to member's account.</p>
</div>
@endif

<form method="POST" action="{{ route('borrowings.process-return',$borrowing) }}">
@csrf

{{-- Condition --}}
<div style="margin-bottom:20px;">
    <label class="form-label">Book Condition on Return <span style="color:#ef4444">*</span></label>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-top:6px;">
        @foreach(['good'=>['Good','#dcfce7','#15803d'],'damaged'=>['Damaged','#fef3c7','#92400e'],'lost'=>['Lost','#fee2e2','#991b1b']] as $val=>[$label,$bg,$color])
        <label style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:10px;border:2px solid {{ $val==='good'?'#bbf7d0':($val==='damaged'?'#fde68a':'#fecaca') }};background:#fff;cursor:pointer;font-size:13.5px;font-weight:500;color:{{ $color }};transition:background 0.15s;">
            <input type="radio" name="condition" value="{{ $val }}" {{ $val==='good'?'checked':'' }} style="accent-color:{{ $color }};">
            {{ $label }}
        </label>
        @endforeach
    </div>
</div>

<div style="margin-bottom:24px;">
    <label class="form-label">Notes</label>
    <textarea name="notes" rows="2" class="form-input" placeholder="Any remarks about this return..."></textarea>
</div>

<div style="display:flex;gap:12px;">
    <button type="submit" class="btn btn-primary">Confirm Return</button>
    <a href="{{ route('borrowings.show',$borrowing) }}" class="btn btn-outline">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
