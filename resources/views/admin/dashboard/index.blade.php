@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')

@section('header-actions')
<span style="font-size:13px;color:#9ca3af;">{{ now()->format('l, F j, Y') }}</span>
@endsection

@section('content')

{{-- Stat cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="card stat-card">
        <div class="stat-icon" style="background:#dbeafe;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#1d4ed8" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <p style="font-size:24px;font-weight:700;color:#111827;margin:0;font-family:'Playfair Display',serif;">{{ number_format($stats['total_books']) }}</p>
            <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">Total Books</p>
            <p style="font-size:12px;color:#15803d;font-weight:500;margin:2px 0 0;">{{ $stats['available_books'] }} available</p>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon" style="background:#dcfce7;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#15803d" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p style="font-size:24px;font-weight:700;color:#111827;margin:0;font-family:'Playfair Display',serif;">{{ number_format($stats['total_members']) }}</p>
            <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">Total Members</p>
            <p style="font-size:12px;color:#15803d;font-weight:500;margin:2px 0 0;">{{ $stats['active_members'] }} active</p>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon" style="background:#fff7ed;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#c2410c" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div>
            <p style="font-size:24px;font-weight:700;color:#111827;margin:0;font-family:'Playfair Display',serif;">{{ number_format($stats['active_borrowings']) }}</p>
            <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">Active Loans</p>
            @if($stats['overdue_count']>0)
            <p style="font-size:12px;color:#dc2626;font-weight:500;margin:2px 0 0;">{{ $stats['overdue_count'] }} overdue</p>
            @else
            <p style="font-size:12px;color:#15803d;font-weight:500;margin:2px 0 0;">None overdue ✓</p>
            @endif
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon" style="background:#fef2f2;">
            <svg fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p style="font-size:24px;font-weight:700;color:#111827;margin:0;font-family:'Playfair Display',serif;">₱{{ number_format($stats['fines_pending'],2) }}</p>
            <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">Pending Fines</p>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px;">
    {{-- Monthly chart --}}
    <div class="card" style="padding:20px;">
        <p style="font-family:'Playfair Display',serif;font-size:15px;color:#111827;margin:0 0 16px;font-weight:600;">Borrowings — Last 6 Months</p>
        <canvas id="borrowingChart" height="110"></canvas>
    </div>
    {{-- Category chart --}}
    <div class="card" style="padding:20px;">
        <p style="font-family:'Playfair Display',serif;font-size:15px;color:#111827;margin:0 0 16px;font-weight:600;">Books by Category</p>
        <canvas id="categoryChart" height="170"></canvas>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
    {{-- Recent activity --}}
    <div class="card" style="overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;">
            <p style="font-family:'Playfair Display',serif;font-size:15px;color:#111827;margin:0;font-weight:600;">Recent Transactions</p>
            <a href="{{ route('borrowings.index') }}" style="font-size:12.5px;color:#1d4ed8;text-decoration:none;font-weight:500;">View all →</a>
        </div>
        <table class="data-table">
            <thead><tr><th>Code</th><th>Book</th><th>Member</th><th>Due</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($recentBorrowings as $b)
            <tr>
                <td><a href="{{ route('borrowings.show',$b) }}" style="font-family:monospace;font-size:12px;color:#1d4ed8;text-decoration:none;">{{ $b->transaction_code }}</a></td>
                <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $b->book->title }}</td>
                <td style="font-size:13px;">{{ $b->member->name }}</td>
                <td style="font-size:13px;color:{{ $b->is_overdue?'#dc2626':'' }};font-weight:{{ $b->is_overdue?'600':'' }};">{{ $b->due_date->format('M d, Y') }}</td>
                <td><span class="badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#9ca3af;padding:28px;">No transactions yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Right sidebar --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Overdue --}}
        @if($overdueList->isNotEmpty())
        <div class="card" style="padding:16px 18px;border-color:#fecaca;">
            <p style="font-family:'Playfair Display',serif;font-size:14px;color:#991b1b;margin:0 0 10px;font-weight:600;">⚠ Overdue ({{ $overdueList->count() }})</p>
            @foreach($overdueList as $b)
            <a href="{{ route('borrowings.show',$b) }}" style="display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid #fff0f0;text-decoration:none;">
                <div style="min-width:0;flex:1;">
                    <p style="font-size:13px;font-weight:500;color:#111827;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $b->book->title }}</p>
                    <p style="font-size:11.5px;color:#6b7280;margin:1px 0 0;">{{ $b->member->name }}</p>
                </div>
                <span style="font-size:12px;font-weight:700;color:#dc2626;flex-shrink:0;margin-left:8px;">{{ $b->days_overdue }}d</span>
            </a>
            @endforeach
            <a href="{{ route('borrowings.overdue') }}" style="display:block;margin-top:10px;font-size:12.5px;color:#dc2626;text-decoration:none;font-weight:600;">View all overdue →</a>
        </div>
        @endif

        {{-- Due soon --}}
        @if($dueSoon->isNotEmpty())
        <div class="card" style="padding:16px 18px;border-color:#fde68a;">
            <p style="font-family:'Playfair Display',serif;font-size:14px;color:#92400e;margin:0 0 10px;font-weight:600;">Due Within 3 Days</p>
            @foreach($dueSoon as $b)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid #fffbeb;">
                <p style="font-size:13px;color:#374151;margin:0;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $b->book->title }}</p>
                <span style="font-size:12px;color:#d97706;font-weight:600;flex-shrink:0;margin-left:8px;">{{ $b->due_date->format('M d') }}</span>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Popular books --}}
        <div class="card" style="padding:16px 18px;">
            <p style="font-family:'Playfair Display',serif;font-size:14px;color:#111827;margin:0 0 10px;font-weight:600;">Most Borrowed</p>
            @foreach($popularBooks as $i=>$book)
            <a href="{{ route('books.show',$book) }}" style="display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f9fafb;text-decoration:none;">
                <p style="font-size:13px;color:#374151;margin:0;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $book->title }}</p>
                <span style="font-size:12px;font-weight:700;color:#6b7280;flex-shrink:0;margin-left:8px;">{{ $book->borrowings_count }}×</span>
            </a>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const colors = ['#1B3A5C','#2C5F8A','#3b82f6','#D4A853','#15803d','#7c3aed','#db2777','#0891b2'];
new Chart(document.getElementById('borrowingChart').getContext('2d'), {
    type:'bar',
    data:{
        labels:@json(array_keys($monthlyData->toArray())),
        datasets:[{label:'Borrowings',data:@json(array_values($monthlyData->toArray())),backgroundColor:'#1B3A5C',borderRadius:6,}]
    },
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}
});
new Chart(document.getElementById('categoryChart').getContext('2d'), {
    type:'doughnut',
    data:{
        labels:@json($categoryStats->pluck('category')),
        datasets:[{data:@json($categoryStats->pluck('count')),backgroundColor:colors,borderWidth:0}]
    },
    options:{responsive:true,plugins:{legend:{position:'bottom',labels:{font:{size:11},padding:8}}}}
});
</script>
@endpush
