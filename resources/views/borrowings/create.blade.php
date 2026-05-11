@extends('layouts.app')

@section('title', 'Issue Book')
@section('page-title', 'Issue a Book')
@section('breadcrumb', 'Create new borrowing transaction')

@section('content')
<div class="max-w-2xl mx-auto">
<div class="card p-6">
    <form method="POST" action="{{ route('borrowings.store') }}">
        @csrf

        {{-- Member Select --}}
        <div class="mb-5">
            <label class="form-label">Member <span class="text-red-500">*</span></label>
            <select name="member_id" class="form-input" required x-data x-on:change="fetchMemberInfo($event.target.value)">
                <option value="">— Select Member —</option>
                @foreach($members as $m)
                    <option value="{{ $m->id }}" {{ old('member_id', $member?->id) == $m->id ? 'selected' : '' }}
                            data-can-borrow="{{ $m->can_borrow ? 'yes' : 'no' }}"
                            data-books="{{ $m->current_books_count }}/{{ $m->max_books_allowed }}"
                            data-fines="{{ $m->outstanding_fines }}">
                        {{ $m->name }} ({{ $m->member_id }}) — {{ ucfirst($m->membership_type) }}
                    </option>
                @endforeach
            </select>

            @if($member && !$member->can_borrow)
                <div class="mt-2 p-3 bg-red-50 rounded-xl border border-red-100 text-red-700 text-sm">
                    ⚠ This member cannot borrow:
                    @if(!$member->is_active) membership is {{ $member->status }}. @endif
                    @if($member->outstanding_fines > 0) has ₱{{ number_format($member->outstanding_fines, 2) }} outstanding fine. @endif
                    @if($member->current_books_count >= $member->max_books_allowed) has reached the borrow limit. @endif
                </div>
            @elseif($member)
                <div class="mt-2 p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-emerald-700 text-sm">
                    ✓ {{ $member->name }} can borrow. Currently has {{ $member->current_books_count }}/{{ $member->max_books_allowed }} books.
                </div>
            @endif
        </div>

        {{-- Book Select --}}
        <div class="mb-5">
            <label class="form-label">Book <span class="text-red-500">*</span></label>
            <select name="book_id" class="form-input" required>
                <option value="">— Select Book —</option>
                @foreach($books as $b)
                    <option value="{{ $b->id }}" {{ old('book_id', $book?->id) == $b->id ? 'selected' : '' }}>
                        {{ $b->title }} — {{ $b->author }} ({{ $b->available_copies }} avail.)
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Notes --}}
        <div class="mb-6">
            <label class="form-label">Notes (Optional)</label>
            <textarea name="notes" rows="2" class="form-input" placeholder="Any special notes...">{{ old('notes') }}</textarea>
        </div>

        <div class="p-4 bg-blue-50 rounded-xl text-sm text-blue-700 mb-6">
            <p>ℹ Default loan period is <strong>14 days</strong>. Fine: <strong>₱5/day</strong> overdue.</p>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">Issue Book</button>
            <a href="{{ route('borrowings.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection
