@extends('layouts.app')

@section('title', $borrowing->transaction_code)
@section('page-title', 'Borrowing Details')
@section('breadcrumb', $borrowing->transaction_code)

@section('header-actions')
    @if(in_array($borrowing->status, ['active','overdue']))
        <a href="{{ route('borrowings.return', $borrowing) }}" class="btn btn-primary">Process Return</a>
    @endif
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-5">

        {{-- Transaction Summary --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-display text-lg text-gray-800">Transaction Info</h2>
                <span class="badge-{{ $borrowing->status }}">{{ ucfirst($borrowing->status) }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <div><dt class="text-xs text-gray-400">Code</dt><dd class="font-mono font-medium">{{ $borrowing->transaction_code }}</dd></div>
                <div><dt class="text-xs text-gray-400">Issued By</dt><dd>{{ $borrowing->issuedBy->name }}</dd></div>
                <div><dt class="text-xs text-gray-400">Borrow Date</dt><dd>{{ $borrowing->borrow_date->format('M d, Y') }}</dd></div>
                <div><dt class="text-xs text-gray-400">Due Date</dt>
                    <dd class="{{ $borrowing->is_overdue ? 'text-red-600 font-semibold' : '' }}">{{ $borrowing->due_date->format('M d, Y') }}</dd>
                </div>
                @if($borrowing->return_date)
                <div><dt class="text-xs text-gray-400">Return Date</dt><dd>{{ $borrowing->return_date->format('M d, Y') }}</dd></div>
                <div><dt class="text-xs text-gray-400">Returned To</dt><dd>{{ $borrowing->returnedTo?->name ?? '—' }}</dd></div>
                @endif
                @if($borrowing->condition_on_return)
                <div><dt class="text-xs text-gray-400">Condition on Return</dt><dd class="capitalize">{{ $borrowing->condition_on_return }}</dd></div>
                @endif
                @if($borrowing->notes)
                <div class="col-span-2"><dt class="text-xs text-gray-400">Notes</dt><dd>{{ $borrowing->notes }}</dd></div>
                @endif
            </dl>
        </div>

        {{-- Fine Info --}}
        @if($borrowing->fine_amount > 0 || $borrowing->is_overdue)
        <div class="card p-5 border-red-100">
            <h3 class="font-display text-sm text-red-700 mb-3">Fine Information</h3>
            <dl class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                <div><dt class="text-xs text-gray-400">Days Overdue</dt><dd class="text-red-600 font-semibold">{{ $borrowing->days_overdue }}</dd></div>
                <div><dt class="text-xs text-gray-400">Fine Amount</dt><dd class="text-red-600 font-semibold">₱{{ number_format($borrowing->fine_amount, 2) }}</dd></div>
                <div><dt class="text-xs text-gray-400">Fine Paid</dt>
                    <dd class="{{ $borrowing->fine_paid ? 'text-emerald-600' : 'text-red-600' }} font-medium">
                        {{ $borrowing->fine_paid ? 'Yes — '.$borrowing->fine_paid_date?->format('M d, Y') : 'No' }}
                    </dd>
                </div>
            </dl>
            @if($borrowing->fine_amount > 0 && !$borrowing->fine_paid && $borrowing->status === 'returned')
                <form method="POST" action="{{ route('borrowings.pay-fine', $borrowing) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-primary text-sm">Mark Fine as Paid</button>
                </form>
            @endif
        </div>
        @endif

        {{-- Renewals --}}
        @if($borrowing->renewals->isNotEmpty())
        <div class="card p-5">
            <h3 class="font-display text-sm text-gray-700 mb-3">Renewal History</h3>
            <table class="w-full data-table">
                <thead><tr><th>#</th><th>Previous Due</th><th>New Due</th><th>Renewed By</th><th>Date</th></tr></thead>
                <tbody>
                @foreach($borrowing->renewals as $i => $r)
                <tr>
                    <td class="text-gray-400">{{ $i + 1 }}</td>
                    <td class="text-xs">{{ $r->previous_due_date->format('M d, Y') }}</td>
                    <td class="text-xs font-medium">{{ $r->new_due_date->format('M d, Y') }}</td>
                    <td class="text-xs">{{ $r->renewedBy->name }}</td>
                    <td class="text-xs text-gray-400">{{ $r->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        {{-- Book --}}
        <div class="card p-5">
            <h3 class="font-display text-sm text-gray-700 mb-3">Book</h3>
            <a href="{{ route('books.show', $borrowing->book) }}" class="group">
                <p class="font-semibold text-gray-800 group-hover:text-blue-700 leading-tight">{{ $borrowing->book->title }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $borrowing->book->author }}</p>
                <p class="text-xs font-mono text-gray-400 mt-1">ISBN: {{ $borrowing->book->isbn }}</p>
            </a>
        </div>

        {{-- Member --}}
        <div class="card p-5">
            <h3 class="font-display text-sm text-gray-700 mb-3">Member</h3>
            <a href="{{ route('members.show', $borrowing->member) }}" class="group">
                <p class="font-semibold text-gray-800 group-hover:text-blue-700">{{ $borrowing->member->name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $borrowing->member->member_id }}</p>
                <p class="text-xs text-gray-400">{{ $borrowing->member->email }}</p>
            </a>
        </div>

        {{-- Actions --}}
        @if(in_array($borrowing->status, ['active', 'overdue']))
        <div class="card p-5 space-y-2">
            <a href="{{ route('borrowings.return', $borrowing) }}" class="btn btn-primary w-full justify-center">Process Return</a>
            @if($borrowing->canRenew())
                <form method="POST" action="{{ route('borrowings.renew', $borrowing) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline w-full justify-center">Renew Loan</button>
                </form>
            @else
                <p class="text-xs text-center text-gray-400">Cannot renew (limit reached or overdue)</p>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
