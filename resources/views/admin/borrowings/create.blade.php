@extends('layouts.admin')
@section('title','Issue Book')
@section('page-title','Issue a Book')
@section('breadcrumb','Create new borrowing transaction')

@section('content')
<div style="max-width:620px;">
<div class="card" style="padding:28px 32px;">

<form method="POST" action="{{ route('borrowings.store') }}">
@csrf

{{-- Member select --}}
<div style="margin-bottom:20px;">
    <label class="form-label">Member <span style="color:#ef4444">*</span></label>
    <select name="member_id" class="form-input" required>
        <option value="">— Select a Member —</option>
        @foreach($members as $m)
        <option value="{{ $m->id }}" {{ old('member_id',$member?->id)==$m->id?'selected':'' }}>
            {{ $m->name }} ({{ $m->member_id }}) — {{ ucfirst($m->membership_type) }} · {{ $m->current_books_count }}/4 books
        </option>
        @endforeach
    </select>
    @if($member && !$member->can_borrow)
    <div style="margin-top:10px;padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;font-size:13.5px;color:#991b1b;">
        ⚠ This member cannot borrow:
        @if(!$member->is_active) membership is <strong>{{ $member->status }}</strong>.@endif
        @if($member->outstanding_fines>0) has <strong>₱{{ number_format($member->outstanding_fines,2) }}</strong> outstanding fine.@endif
        @if($member->current_books_count>=4) has reached the <strong>4-book limit</strong>.@endif
    </div>
    @elseif($member)
    <div style="margin-top:10px;padding:12px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;font-size:13.5px;color:#166534;">
        ✓ <strong>{{ $member->name }}</strong> can borrow. Currently has {{ $member->current_books_count }}/4 books. Can borrow {{ $member->books_remaining }} more.
    </div>
    @endif
</div>

{{-- Book select --}}
<div style="margin-bottom:20px;">
    <label class="form-label">Book <span style="color:#ef4444">*</span></label>
    <select name="book_id" class="form-input" required>
        <option value="">— Select a Book —</option>
        @foreach($books as $b)
        <option value="{{ $b->id }}" {{ old('book_id',$book?->id)==$b->id?'selected':'' }}>
            {{ $b->title }} — {{ $b->author }} ({{ $b->available_copies }} available)
        </option>
        @endforeach
    </select>
</div>

{{-- Notes --}}
<div style="margin-bottom:24px;">
    <label class="form-label">Notes (Optional)</label>
    <textarea name="notes" rows="2" class="form-input" placeholder="Any special remarks...">{{ old('notes') }}</textarea>
</div>

{{-- Info box --}}
<div style="padding:14px 16px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;font-size:13.5px;color:#1e40af;margin-bottom:24px;">
    ℹ Default loan period is <strong>14 days</strong>. Fine rate: <strong>₱5.00/day</strong> overdue. Max renewals: <strong>2</strong>.
</div>

<div style="display:flex;gap:12px;">
    <button type="submit" class="btn btn-primary">Issue Book</button>
    <a href="{{ route('borrowings.index') }}" class="btn btn-outline">Cancel</a>
</div>
</form>
</div>
</div>
@endsection
