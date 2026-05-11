@extends('layouts.admin')
@section('title','Reports')
@section('page-title','Reports & Analytics')

@section('content')

<div class="card" style="padding:16px 20px;margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
        <div><label class="form-label">From</label>
            <input type="date" name="from" value="{{ $from }}" class="form-input"></div>
        <div><label class="form-label">To</label>
            <input type="date" name="to" value="{{ $to }}" class="form-input"></div>
        <button type="submit" class="btn btn-primary btn-sm">Generate Report</button>
    </form>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;">
    <div class="card stat-card">
        <div class="stat-icon" style="background:#dbeafe;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#1d4ed8" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <p style="font-size:28px;font-weight:700;color:#111827;margin:0;font-family:'Playfair Display',serif;">{{ $borrowingsByType->sum() }}</p>
            <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">Total Transactions</p>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon" style="background:#dcfce7;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#15803d" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p style="font-size:28px;font-weight:700;color:#111827;margin:0;font-family:'Playfair Display',serif;">{{ $borrowingsByType->get('returned',0) }}</p>
            <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">Returned</p>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon" style="background:#fef2f2;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p style="font-size:28px;font-weight:700;color:#111827;margin:0;font-family:'Playfair Display',serif;">₱{{ number_format($finesCollected,2) }}</p>
            <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">Fines Collected</p>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <div class="card" style="overflow:hidden;">
        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
            <p style="font-family:'Playfair Display',serif;font-size:15px;color:#111827;margin:0;font-weight:600;">Top Borrowers</p>
        </div>
        <table class="data-table">
            <thead><tr><th>#</th><th>Member</th><th>Type</th><th>Count</th></tr></thead>
            <tbody>
            @forelse($topBorrowers as $i=>$m)
            <tr>
                <td style="color:#9ca3af;font-size:13px;">{{ $i+1 }}</td>
                <td><a href="{{ route('members.show',$m) }}" style="font-size:13.5px;font-weight:500;color:#1d4ed8;text-decoration:none;">{{ $m->name }}</a></td>
                <td><span style="font-size:11.5px;background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:20px;font-weight:500;text-transform:capitalize;">{{ $m->membership_type }}</span></td>
                <td style="font-weight:700;font-size:15px;color:#111827;">{{ $m->borrowings_count }}</td>
            </tr>
            @empty<tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:24px;">No data for this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card" style="overflow:hidden;">
        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
            <p style="font-family:'Playfair Display',serif;font-size:15px;color:#111827;margin:0;font-weight:600;">Most Borrowed Books</p>
        </div>
        <table class="data-table">
            <thead><tr><th>#</th><th>Book</th><th>Category</th><th>Count</th></tr></thead>
            <tbody>
            @forelse($topBooks as $i=>$b)
            <tr>
                <td style="color:#9ca3af;font-size:13px;">{{ $i+1 }}</td>
                <td><a href="{{ route('books.show',$b) }}" style="font-size:13.5px;font-weight:500;color:#1d4ed8;text-decoration:none;">{{ Str::limit($b->title,30) }}</a></td>
                <td style="font-size:12.5px;color:#6b7280;">{{ $b->category }}</td>
                <td style="font-weight:700;font-size:15px;color:#111827;">{{ $b->borrowings_count }}</td>
            </tr>
            @empty<tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:24px;">No data for this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
