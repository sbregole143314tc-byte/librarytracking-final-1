@extends('layouts.admin')
@section('title','Members')
@section('page-title','Library Members')
@section('header-actions')
<a href="{{ route('members.create') }}" class="btn btn-primary btn-sm">+ Register Member</a>
@endsection

@section('content')
<div class="card" style="padding:16px 20px;margin-bottom:18px;">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:200px;"><label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, member ID..." class="form-input"></div>
        <div style="width:140px;"><label class="form-label">Type</label>
            <select name="type" class="form-input"><option value="">All Types</option>
            @foreach(['student','faculty','staff','public'] as $t)<option value="{{ $t }}" {{ request('type')==$t?'selected':'' }}>{{ ucfirst($t) }}</option>@endforeach</select></div>
        <div style="width:140px;"><label class="form-label">Status</label>
            <select name="status" class="form-input"><option value="">All Status</option>
            @foreach(['active','suspended','expired','blacklisted'] as $s)<option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('members.index') }}" class="btn btn-outline btn-sm">Clear</a>
    </form>
</div>

<div class="card" style="overflow:hidden;">
    <table class="data-table">
        <thead><tr><th>Member</th><th>ID</th><th>Type</th><th>Expiry</th><th>Books</th><th>Fines</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($members as $m)
        <tr>
            <td>
                <a href="{{ route('members.show',$m) }}" style="font-weight:600;font-size:13.5px;color:#111827;text-decoration:none;">{{ $m->name }}</a>
                <p style="font-size:12px;color:#9ca3af;margin:1px 0 0;">{{ $m->email }}</p>
            </td>
            <td style="font-family:monospace;font-size:12px;color:#6b7280;">{{ $m->member_id }}</td>
            <td><span style="font-size:11.5px;background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:20px;font-weight:500;text-transform:capitalize;">{{ $m->membership_type }}</span></td>
            <td style="font-size:13px;color:{{ $m->is_expired?'#dc2626':'' }};font-weight:{{ $m->is_expired?'600':'' }};">{{ $m->membership_expiry->format('M d, Y') }}</td>
            <td style="text-align:center;">
                <span style="font-size:13.5px;font-weight:{{ $m->current_books_count>0?'700':'400' }};color:{{ $m->current_books_count>0?'#111827':'#9ca3af' }};">{{ $m->current_books_count }}/4</span>
            </td>
            <td style="font-size:13px;font-weight:{{ $m->outstanding_fines>0?'700':'' }};color:{{ $m->outstanding_fines>0?'#dc2626':'#9ca3af' }};">
                {{ $m->outstanding_fines>0?'₱'.number_format($m->outstanding_fines,2):'—' }}
            </td>
            <td><span class="badge badge-{{ $m->status==='active'?'active':($m->status==='suspended'?'overdue':'returned') }}">{{ ucfirst($m->status) }}</span></td>
            <td>
                <div style="display:flex;gap:10px;">
                    <a href="{{ route('members.show',$m) }}" style="font-size:12.5px;color:#1d4ed8;text-decoration:none;">View</a>
                    <a href="{{ route('members.edit',$m) }}" style="font-size:12.5px;color:#6b7280;text-decoration:none;">Edit</a>
                    @if($m->is_active)<a href="{{ route('borrowings.create',['member_id'=>$m->id]) }}" style="font-size:12.5px;color:#15803d;text-decoration:none;">Issue</a>@endif
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:32px;">No members found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:14px;">{{ $members->links() }}</div>
@endsection
