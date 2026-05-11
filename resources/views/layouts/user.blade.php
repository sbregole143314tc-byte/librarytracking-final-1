<!DOCTYPE html>
<html lang="en" style="height:100%">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','My Library') — BookTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root{--brand:#1B3A5C;--brand-light:#2C5F8A;--accent:#D4A853;--surface:#F5F7FA;}
        *{box-sizing:border-box;}
        body{font-family:'DM Sans',sans-serif;background:var(--surface);margin:0;min-height:100%;}
        .font-display{font-family:'Playfair Display',serif;}

        /* Topnav */
        .topnav{background:#fff;border-bottom:2px solid #e8edf5;position:sticky;top:0;z-index:50;}
        .topnav-inner{max-width:1100px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:60px;}
        .logo{display:flex;align-items:center;gap:10px;text-decoration:none;}
        .logo-icon{width:34px;height:34px;border-radius:8px;background:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .logo-text{font-family:'Playfair Display',serif;font-size:20px;color:#1a202c;font-weight:600;}

        /* Nav links */
        .nav-links{display:flex;align-items:center;gap:4px;}
        .nav-link{display:flex;align-items:center;gap:6px;padding:7px 13px;border-radius:8px;color:#4b5563;font-size:13.5px;font-weight:500;text-decoration:none;transition:all 0.15s;position:relative;}
        .nav-link:hover{background:#eef2f8;color:var(--brand);}
        .nav-link.active{background:#dbeafe;color:#1d4ed8;font-weight:600;}
        .nav-link svg{width:15px;height:15px;flex-shrink:0;}
        .nav-badge{position:absolute;top:-2px;right:-4px;width:16px;height:16px;border-radius:50%;background:#ef4444;color:#fff;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;}

        /* User menu */
        .user-avatar{width:34px;height:34px;border-radius:50%;background:var(--brand);display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;cursor:pointer;flex-shrink:0;}
        .dropdown{position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.12);min-width:180px;z-index:100;overflow:hidden;}

        /* Content */
        .page-wrap{max-width:1100px;margin:0 auto;padding:28px 24px;}

        /* Cards */
        .card{background:#fff;border-radius:14px;border:1px solid #e8edf5;box-shadow:0 1px 4px rgba(27,58,92,0.06);}
        .card-hover:hover{box-shadow:0 4px 16px rgba(27,58,92,0.12);transform:translateY(-1px);transition:all 0.2s;}

        /* Buttons */
        .btn{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:10px;font-weight:500;font-size:14px;cursor:pointer;text-decoration:none;border:none;transition:all 0.15s;font-family:'DM Sans',sans-serif;}
        .btn-primary{background:var(--brand);color:#fff;}
        .btn-primary:hover{background:var(--brand-light);}
        .btn-outline{background:#fff;color:#374151;border:1px solid #d1d5db;}
        .btn-outline:hover{background:#f9fafb;border-color:#9ca3af;}
        .btn-sm{padding:6px 13px;font-size:13px;border-radius:8px;}

        /* Form */
        .form-input{display:block;width:100%;border:1.5px solid #d1d5db;border-radius:10px;padding:10px 14px;font-size:14px;font-family:'DM Sans',sans-serif;outline:none;transition:border 0.15s;}
        .form-input:focus{border-color:var(--brand-light);box-shadow:0 0 0 3px rgba(44,95,138,0.15);}
        .form-label{display:block;font-size:13.5px;font-weight:500;color:#374151;margin-bottom:5px;}

        /* Badges */
        .badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:11.5px;font-weight:600;}
        .badge-active{background:#dcfce7;color:#15803d;}
        .badge-overdue{background:#fee2e2;color:#b91c1c;}
        .badge-returned{background:#f1f5f9;color:#475569;}
        .badge-warning{background:#fef9c3;color:#a16207;}

        /* Alerts */
        .alert{padding:14px 16px;border-radius:12px;font-size:14px;}
        .alert-danger{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;}
        .alert-warning{background:#fffbeb;border:1px solid #fde68a;color:#92400e;}
        .alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;}
        .alert-info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;}

        /* Divider */
        hr{border:none;border-top:1px solid #e8edf5;margin:0;}
    </style>
</head>
<body>

{{-- Top Navigation --}}
<nav class="topnav">
    <div class="topnav-inner">
        <a href="{{ route('user.dashboard') }}" class="logo">
            <div class="logo-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <span class="logo-text">BookTrack</span>
        </a>

        <div class="nav-links">
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Home
            </a>
            <a href="{{ route('user.books') }}" class="nav-link {{ request()->routeIs('user.books') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                My Books
            </a>
            <a href="{{ route('user.available') }}" class="nav-link {{ request()->routeIs('user.available') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Browse
            </a>
            <a href="{{ route('user.overdue') }}" class="nav-link {{ request()->routeIs('user.overdue') ? 'active' : '' }}" style="position:relative">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Overdue
                @php $overdueCount = auth()->user()->member?->activeBorrowings()->where(fn($q)=>$q->where('status','overdue')->orWhere(fn($q2)=>$q2->where('status','active')->where('due_date','<',now()->toDateString())))->count() ?? 0; @endphp
                @if($overdueCount > 0)<span class="nav-badge">{{ $overdueCount }}</span>@endif
            </a>
            <a href="{{ route('user.history') }}" class="nav-link {{ request()->routeIs('user.history') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                History
            </a>
        </div>

        <div style="position:relative" x-data="{open:false}">
            <button @click="open=!open" style="display:flex;align-items:center;gap:8px;background:none;border:none;cursor:pointer;padding:4px;">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name??'U',0,1)) }}</div>
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#6b7280" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="dropdown" x-show="open" @click.outside="open=false" x-transition style="display:none">
                <div style="padding:12px 16px;border-bottom:1px solid #f3f4f6;">
                    <p style="font-weight:600;font-size:13.5px;color:#111827;margin:0">{{ auth()->user()->name }}</p>
                    <p style="font-size:11.5px;color:#6b7280;margin:2px 0 0;font-family:monospace">{{ auth()->user()->member?->member_id ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width:100%;text-align:left;padding:10px 16px;font-size:13.5px;color:#dc2626;background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Overdue global banner --}}
@php $member = auth()->user()->member; @endphp
@if($member && $member->has_overdue)
<div style="background:#dc2626;color:#fff;padding:10px 24px;display:flex;align-items:center;justify-content:center;gap:12px;font-size:13.5px;font-weight:500;" x-data="{show:true}" x-show="show">
    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    <span>You have overdue books! Please return them immediately to avoid additional fines.</span>
    <a href="{{ route('user.overdue') }}" style="color:#fde68a;font-weight:700;text-decoration:underline;">View →</a>
    <button @click="show=false" style="background:none;border:none;color:rgba(255,255,255,0.7);cursor:pointer;margin-left:8px;font-size:16px;line-height:1;padding:0">✕</button>
</div>
@endif

{{-- Flash messages --}}
<div class="page-wrap" style="padding-bottom:0">
    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:0" x-data x-init="setTimeout(()=>$el.remove(),5000)">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger" style="margin-bottom:0">{{ session('error') }}</div>
    @endif
</div>

<div class="page-wrap">
    @yield('content')
</div>

</body>
</html>
