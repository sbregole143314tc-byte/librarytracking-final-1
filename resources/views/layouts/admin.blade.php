<!DOCTYPE html>
<html lang="en" style="height:100%">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — BookTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --brand:#1B3A5C; --accent:#D4A853; }
        body { font-family:'DM Sans',sans-serif; background:#F0F2F5; margin:0; height:100%; }
        .font-display { font-family:'Playfair Display',serif; }
        #sidebar { background:var(--brand); width:240px; min-width:240px; display:flex; flex-direction:column; overflow-y:auto; flex-shrink:0; }
        .nav-link { display:flex; align-items:center; gap:10px; padding:9px 16px; border-radius:8px; color:#93c5fd; font-size:13.5px; text-decoration:none; transition:background 0.15s,color 0.15s; }
        .nav-link:hover { background:rgba(255,255,255,0.1); color:#fff; }
        .nav-link.active { background:rgba(255,255,255,0.15); color:#fff; font-weight:500; }
        .nav-link svg { width:16px !important; height:16px !important; flex-shrink:0; }
        .nav-section { padding:20px 16px 4px; font-size:10px; font-weight:600; color:rgba(147,197,253,0.6); text-transform:uppercase; letter-spacing:0.08em; }
        .card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06); border:1px solid #f3f4f6; }
        .btn { display:inline-flex; align-items:center; gap:8px; padding:8px 16px; border-radius:12px; font-weight:500; font-size:14px; transition:opacity 0.15s; cursor:pointer; text-decoration:none; border:none; font-family:'DM Sans',sans-serif; }
        .btn-primary { background:var(--brand); color:#fff; }
        .btn-primary:hover { opacity:0.9; }
        .btn-accent { background:var(--accent); color:#fff; }
        .btn-danger { background:#dc2626; color:#fff; }
        .btn-danger:hover { background:#b91c1c; }
        .btn-outline { background:#fff; color:#374151; border:1px solid #e5e7eb !important; }
        .btn-outline:hover { background:#f9fafb; }
        .btn-sm { padding:6px 12px !important; font-size:12px !important; border-radius:8px !important; }
        .form-input { display:block; width:100%; border-radius:12px; border:1px solid #e5e7eb; font-size:14px; padding:10px 14px; outline:none; box-sizing:border-box; font-family:'DM Sans',sans-serif; }
        .form-input:focus { border-color:#60a5fa; box-shadow:0 0 0 3px rgba(96,165,250,0.2); }
        .form-label { display:block; font-size:14px; font-weight:500; color:#374151; margin-bottom:4px; }
        .stat-card { display:flex; align-items:flex-start; gap:16px; padding:20px; }
        .stat-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .stat-icon svg { width:22px; height:22px; }
        .data-table { width:100%; border-collapse:collapse; }
        .data-table th { font-size:11px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:0.05em; padding:10px 16px; text-align:left; background:#f9fafb; }
        .data-table td { padding:11px 16px; font-size:13.5px; border-top:1px solid #f9fafb; }
        .data-table tbody tr:hover { background:#eff6ff; }
        .badge { display:inline-flex; padding:2px 8px; border-radius:999px; font-size:11px; font-weight:500; }
        .badge-active   { background:#d1fae5; color:#065f46; }
        .badge-overdue  { background:#fee2e2; color:#991b1b; }
        .badge-returned { background:#f3f4f6; color:#4b5563; }
        .badge-pending  { background:#fef3c7; color:#92400e; }
        .badge-lost     { background:#ffedd5; color:#9a3412; }
    </style>
    @stack('styles')
</head>
<body style="height:100%; margin:0">
<div style="display:flex; height:100vh; overflow:hidden">

    <aside id="sidebar">
        <div style="padding:20px; border-bottom:1px solid rgba(255,255,255,0.1)">
            <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:12px; text-decoration:none">
                <div style="width:32px; height:32px; border-radius:8px; background:var(--accent); display:flex; align-items:center; justify-content:center; flex-shrink:0">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <p style="color:#fff; font-weight:600; font-size:14px; line-height:1.2; margin:0">BookTrack</p>
                    <p style="color:#93c5fd; font-size:11px; margin:0">Admin Panel</p>
                </div>
            </a>
        </div>

        <nav style="flex:1; padding:12px; display:flex; flex-direction:column; gap:2px">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <div class="nav-section">Catalog</div>
            <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.index','books.show') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                All Books
            </a>
            <a href="{{ route('books.create') }}" class="nav-link {{ request()->routeIs('books.create','books.edit') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Book
            </a>

            <div class="nav-section">Members</div>
            <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.index','members.show') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                All Members
            </a>

            <div class="nav-section">Circulation</div>
            <a href="{{ route('borrowings.index') }}" class="nav-link {{ request()->routeIs('borrowings.index') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                All Transactions
            </a>
            <a href="{{ route('borrowings.borrowed') }}" class="nav-link {{ request()->routeIs('borrowings.borrowed') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Currently Borrowed
            </a>
            <a href="{{ route('borrowings.available') }}" class="nav-link {{ request()->routeIs('borrowings.available') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Available to Borrow
            </a>
            <a href="{{ route('borrowings.overdue') }}" class="nav-link {{ request()->routeIs('borrowings.overdue') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Overdue Books
            </a>
            <a href="{{ route('borrowings.create') }}" class="nav-link {{ request()->routeIs('borrowings.create') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Issue Book
            </a>

            <div class="nav-section">Reports</div>
            <a href="{{ route('reports') }}" class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Reports
            </a>
        </nav>

        <div style="padding:12px 16px; border-top:1px solid rgba(255,255,255,0.1)">
            <div style="display:flex; align-items:center; gap:10px">
                <div style="width:32px; height:32px; border-radius:50%; background:var(--accent); display:flex; align-items:center; justify-content:center; color:#fff; font-size:12px; font-weight:700; flex-shrink:0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div style="flex:1; min-width:0">
                    <p style="color:#fff; font-size:12px; font-weight:500; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis">{{ auth()->user()->name }}</p>
                    <p style="color:#93c5fd; font-size:11px; margin:0">Administrator</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" style="background:none; border:none; cursor:pointer; color:#93c5fd; padding:4px; line-height:0; display:flex">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main style="flex:1; display:flex; flex-direction:column; overflow:hidden">
        <header style="background:#fff; border-bottom:1px solid #f3f4f6; padding:14px 24px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0">
            <div>
                <h1 style="font-family:'Playfair Display',serif; font-size:18px; color:#111827; margin:0">@yield('page-title', 'Dashboard')</h1>
                @hasSection('breadcrumb')<p style="font-size:12px; color:#9ca3af; margin:2px 0 0">@yield('breadcrumb')</p>@endif
            </div>
            <div style="display:flex; align-items:center; gap:12px">@yield('header-actions')</div>
        </header>

        @if(session('success'))
        <div style="margin:16px 24px 0; padding:12px 16px; border-radius:12px; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; font-size:14px; display:flex; align-items:center; gap:8px" x-data x-init="setTimeout(()=>$el.remove(),5000)">
            <svg width="16" height="16" fill="#059669" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="margin:16px 24px 0; padding:12px 16px; border-radius:12px; background:#fef2f2; border:1px solid #fecaca; color:#991b1b; font-size:14px">
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div style="margin:16px 24px 0; padding:12px 16px; border-radius:12px; background:#fef2f2; border:1px solid #fecaca; color:#991b1b; font-size:14px">
            <ul style="margin:0; padding-left:16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div style="flex:1; overflow-y:auto; padding:24px">
            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')
</body>
</html>
