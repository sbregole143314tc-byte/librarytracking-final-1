<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Library') — BookTrack</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Tailwind CSS (CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Chart.js (for dashboard) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --brand:     #1B3A5C;
            --brand-mid: #2C5F8A;
            --accent:    #D4A853;
            --success:   #2D8A4E;
            --danger:    #C0392B;
            --warning:   #D97706;
            --surface:   #F7F5F2;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--surface); }
        h1,h2,.font-display { font-family: 'Playfair Display', serif; }

        /* Sidebar */
        #sidebar { background: var(--brand); }
        .nav-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-lg text-blue-200 hover:bg-white/10 hover:text-white transition-all; }
        .nav-link.active { @apply bg-white/15 text-white font-medium; }
        .nav-link svg { @apply w-5 h-5 flex-shrink-0; }

        /* Cards */
        .card { @apply bg-white rounded-2xl shadow-sm border border-gray-100; }

        /* Badges */
        .badge-active   { @apply inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700; }
        .badge-overdue  { @apply inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700; }
        .badge-returned { @apply inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600; }
        .badge-pending  { @apply inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700; }

        /* Inputs */
        .form-input { @apply block w-full rounded-xl border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 text-sm py-2.5 px-3.5; }
        .form-label { @apply block text-sm font-medium text-gray-700 mb-1; }

        /* Buttons */
        .btn { @apply inline-flex items-center gap-2 px-4 py-2 rounded-xl font-medium text-sm transition-all; }
        .btn-primary { background: var(--brand); @apply text-white hover:opacity-90; }
        .btn-accent  { background: var(--accent); @apply text-white hover:opacity-90; }
        .btn-danger  { background: var(--danger); @apply text-white hover:opacity-90; }
        .btn-outline { @apply border border-gray-200 text-gray-700 bg-white hover:bg-gray-50; }

        /* Stat card */
        .stat-card { @apply card p-5 flex items-start gap-4; }
        .stat-icon { @apply w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0; }

        /* Table */
        .data-table th { @apply text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 text-left; }
        .data-table td { @apply px-4 py-3 text-sm; }
        .data-table tbody tr { @apply hover:bg-gray-50 border-t border-gray-50; }
    </style>

    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside id="sidebar" class="w-64 flex-shrink-0 flex flex-col overflow-y-auto transition-transform"
           :class="{ '-translate-x-full': !sidebarOpen }"
           style="min-width:256px">

        {{-- Brand --}}
        <div class="px-6 py-5 border-b border-white/10">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background:var(--accent)">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-display text-white text-lg leading-tight">BookTrack</span>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Catalog</p>
            <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Books
            </a>
            <a href="{{ route('books.create') }}" class="nav-link {{ request()->routeIs('books.create') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Book
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Members</p>
            <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.index') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Members
            </a>
            <a href="{{ route('members.create') }}" class="nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Register Member
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Circulation</p>
            <a href="{{ route('borrowings.index') }}" class="nav-link {{ request()->routeIs('borrowings.index') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Borrowings
            </a>
            <a href="{{ route('borrowings.create') }}" class="nav-link">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Issue Book
            </a>
            <a href="{{ route('borrowings.overdue') }}" class="nav-link {{ request()->routeIs('borrowings.overdue') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Overdue Books
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Reports</p>
            <a href="{{ route('reports') }}" class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Reports
            </a>
        </nav>

        {{-- User --}}
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white text-xs font-semibold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name ?? 'User' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-blue-300 hover:text-white transition-colors" title="Logout">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-100 px-6 py-3 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="font-display text-xl text-gray-900">@yield('page-title', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                    <p class="text-xs text-gray-400 mt-0.5">@yield('breadcrumb')</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @yield('header-actions')
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="mx-6 mt-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2" x-data x-init="setTimeout(()=>$el.remove(),5000)">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-6 mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="mx-6 mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Page content --}}
        <div class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>
