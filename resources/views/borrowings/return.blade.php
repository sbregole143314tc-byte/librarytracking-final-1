@extends('layouts.app')
@section('title','Return Book')
@section('page-title','Process Book Return')

@section('content')
<div class="max-w-xl mx-auto">
<div class="card p-6">

    <div class="p-4 bg-gray-50 rounded-xl mb-6 flex gap-4">
        <div class="flex-1">
            <p class="text-xs text-gray-400">Book</p>
            <p class="font-semibold text-gray-800">{{ $borrowing->book->title }}</p>
            <p class="text-xs text-gray-500">{{ $borrowing->book->author }}</p>
        </div>
        <div class="flex-1">
            <p class="text-xs text-gray-400">Member</p>
            <p class="font-semibold text-gray-800">{{ $borrowing->member->name }}</p>
            <p class="text-xs text-gray-500">{{ $borrowing->member->member_id }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-5 text-sm">
        <div class="p-3 rounded-lg bg-gray-50">
            <p class="text-xs text-gray-400">Borrowed</p>
            <p class="font-medium">{{ $borrowing->borrow_date->format('M d, Y') }}</p>
        </div>
        <div class="p-3 rounded-lg {{ $borrowing->is_overdue ? 'bg-red-50' : 'bg-gray-50' }}">
            <p class="text-xs text-gray-400">Due Date</p>
            <p class="font-medium {{ $borrowing->is_overdue ? 'text-red-600' : '' }}">{{ $borrowing->due_date->format('M d, Y') }}</p>
        </div>
    </div>

    @if($fine > 0)
    <div class="p-4 bg-red-50 rounded-xl border border-red-100 mb-5">
        <p class="text-sm font-semibold text-red-700">⚠ Overdue Fine: ₱{{ number_format($fine, 2) }}</p>
        <p class="text-xs text-red-500 mt-0.5">{{ $borrowing->days_overdue }} days × ₱5.00/day</p>
    </div>
    @endif

    <form method="POST" action="{{ route('borrowings.process-return', $borrowing) }}">
        @csrf

        <div class="mb-4">
            <label class="form-label">Book Condition on Return <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-3 gap-3 mt-1">
                @foreach(['good' => 'Good', 'damaged' => 'Damaged', 'lost' => 'Lost'] as $val => $label)
                <label class="flex items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all
                    {{ $val === 'good' ? 'border-emerald-200 hover:border-emerald-400' : ($val === 'damaged' ? 'border-amber-200 hover:border-amber-400' : 'border-red-200 hover:border-red-400') }}">
                    <input type="radio" name="condition" value="{{ $val }}" {{ $val === 'good' ? 'checked' : '' }} class="sr-only">
                    <span class="text-sm font-medium">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="mb-6">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="2" class="form-input" placeholder="Any remarks about the return..."></textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">Confirm Return</button>
            <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection
