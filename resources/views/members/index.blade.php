@extends('layouts.app')

@section('title', 'Members')
@section('page-title', 'Library Members')

@section('header-actions')
    <a href="{{ route('members.create') }}" class="btn btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        Register Member
    </a>
@endsection

@section('content')

<div class="card p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, member ID..." class="form-input">
        </div>
        <div class="w-36">
            <label class="form-label">Type</label>
            <select name="type" class="form-input">
                <option value="">All Types</option>
                @foreach(['student','faculty','staff','public'] as $t)
                    <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-36">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="">All Status</option>
                @foreach(['active','suspended','expired','blacklisted'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('members.index') }}" class="btn btn-outline">Clear</a>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full data-table">
        <thead>
            <tr class="bg-gray-50">
                <th>Member ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Expiry</th>
                <th>Books</th>
                <th>Fines</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($members as $member)
            <tr>
                <td class="font-mono text-xs text-gray-500">{{ $member->member_id }}</td>
                <td>
                    <a href="{{ route('members.show', $member) }}" class="font-medium text-gray-900 hover:text-blue-700">{{ $member->name }}</a>
                </td>
                <td class="text-gray-500 text-xs">{{ $member->email }}</td>
                <td><span class="text-xs capitalize px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">{{ $member->membership_type }}</span></td>
                <td class="{{ $member->is_expired ? 'text-red-500 font-medium' : 'text-gray-600' }} text-xs">
                    {{ $member->membership_expiry->format('M d, Y') }}
                </td>
                <td class="text-center">
                    <span class="text-xs {{ $member->current_books_count > 0 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                        {{ $member->current_books_count }}/{{ $member->max_books_allowed }}
                    </span>
                </td>
                <td class="{{ $member->outstanding_fines > 0 ? 'text-red-600 font-semibold' : 'text-gray-400' }} text-xs">
                    {{ $member->outstanding_fines > 0 ? '₱'.number_format($member->outstanding_fines, 2) : '—' }}
                </td>
                <td><span class="badge-{{ $member->status == 'active' ? 'active' : ($member->status == 'suspended' ? 'overdue' : 'returned') }}">{{ ucfirst($member->status) }}</span></td>
                <td>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('members.show', $member) }}" class="text-xs text-blue-600 hover:underline">View</a>
                        <a href="{{ route('members.edit', $member) }}" class="text-xs text-gray-500 hover:underline">Edit</a>
                        @if($member->is_active)
                            <a href="{{ route('borrowings.create', ['member_id' => $member->id]) }}" class="text-xs text-emerald-600 hover:underline">Issue</a>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="9" class="text-center text-gray-400 py-8">No members found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $members->links() }}</div>

@endsection
