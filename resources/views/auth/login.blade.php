<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isAdmin ? 'Admin Login' : 'Member Login' }} — Libraryracking</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;}
        .font-display{font-family:'Playfair Display',serif;}
        input{font-family:'DM Sans',sans-serif;}
        .field-label{display:block;font-size:13.5px;font-weight:500;color:#374151;margin-bottom:6px;}
        .field-input{display:block;width:100%;border:1.5px solid #d1d5db;border-radius:10px;padding:11px 14px;font-size:14px;outline:none;transition:border 0.15s,box-shadow 0.15s;}
        .field-input:focus{border-color:#2C5F8A;box-shadow:0 0 0 3px rgba(44,95,138,0.15);}
        .btn-submit{width:100%;padding:12px;border-radius:10px;font-size:14.5px;font-weight:600;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;transition:opacity 0.15s;}
        .btn-submit:hover{opacity:0.9;}
        .error-box{padding:12px 14px;border-radius:10px;font-size:13.5px;margin-bottom:16px;}
    </style>
</head>
<body>

@if($isAdmin)
{{-- ═══════════════ ADMIN LOGIN ═══════════════ --}}
<div style="flex:1;background:#1B3A5C;display:flex;align-items:center;justify-content:center;padding:40px 24px;">
    <div style="width:100%;max-width:400px;">

        {{-- Logo --}}
        <div style="text-align:center;margin-bottom:36px;">
            <div style="width:64px;height:64px;border-radius:16px;background:#D4A853;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 style="font-family:'Playfair Display',serif;font-size:28px;color:#fff;font-weight:700;">LibraryTrack</h1>
            <p style="color:#93c5fd;font-size:14px;margin-top:4px;">Administrator Portal</p>
        </div>

        {{-- Card --}}
        <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:16px;padding:32px;">

            @if($errors->any())
            <div class="error-box" style="background:rgba(220,38,38,0.2);border:1px solid rgba(220,38,38,0.4);color:#fca5a5;">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div style="margin-bottom:16px;">
                    <label class="field-label" style="color:#bfdbfe;">Admin Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="field-input" placeholder="admin@booktrack.com"
                           style="background:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.15);color:#fff;">
                </div>
                <div style="margin-bottom:24px;">
                    <label class="field-label" style="color:#bfdbfe;">Password</label>
                    <input type="password" name="password" required
                           class="field-input" placeholder="••••••••"
                           style="background:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.15);color:#fff;">
                </div>
                <button type="submit" class="btn-submit" style="background:#D4A853;color:#fff;">
                    Sign In as Administrator
                </button>
            </form>

        </div>

        <p style="text-align:center;color:rgba(147,197,253,0.5);font-size:12px;margin-top:24px;">LibraryTrack Library Management System</p>
    </div>
</div>

@else
{{-- ═══════════════ USER LOGIN ═══════════════ --}}
{{-- Left panel --}}
<div style="display:none;background:#1B3A5C;width:45%;align-items:center;justify-content:center;padding:60px 48px;flex-direction:column;" id="left-panel">
    <div style="max-width:340px;text-align:center;">
        <div style="width:72px;height:72px;border-radius:18px;background:#D4A853;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h1 style="font-family:'Playfair Display',serif;font-size:36px;color:#fff;font-weight:700;margin-bottom:12px;">LibraryTrack</h1>
        <p style="color:#93c5fd;font-size:15px;line-height:1.6;margin-bottom:36px;">Your personal library. Borrow, return, and explore thousands of books.</p>

        <div style="text-align:left;background:rgba(255,255,255,0.07);border-radius:14px;padding:20px 24px;">
            <p style="color:#bfdbfe;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:14px;">Member Benefits</p>
            @foreach(['Borrow up to 4 books at once','14-day loan periods with 2 renewals','Overdue warnings & fine tracking','Full borrowing history'] as $f)
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                <div style="width:20px;height:20px;border-radius:50%;background:#D4A853;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="11" height="11" fill="white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <span style="color:#e0eeff;font-size:14px;">{{ $f }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Right panel --}}
<div style="flex:1;background:#fff;display:flex;align-items:center;justify-content:center;padding:40px 24px;">
    <div style="width:100%;max-width:400px;">
        {{-- Mobile logo --}}
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:32px;">
            <div style="width:36px;height:36px;border-radius:9px;background:#1B3A5C;display:flex;align-items:center;justify-content:center;">
                <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <span style="font-family:'Playfair Display',serif;font-size:22px;color:#111827;font-weight:600;">LibraryTrack</span>
        </div>

        <h2 style="font-family:'Playfair Display',serif;font-size:26px;color:#111827;margin:0 0 6px;font-weight:700;">Welcome back</h2>
        <p style="font-size:14px;color:#6b7280;margin:0 0 28px;">Sign in to your member account</p>

        @if($errors->any())
        <div class="error-box" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;">
            {{ $errors->first() }}
        </div>
        @endif
        @if(session('status'))
        <div class="error-box" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label class="field-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="field-input" placeholder="you@example.com">
            </div>
            <div style="margin-bottom:24px;">
                <label class="field-label">Password</label>
                <input type="password" name="password" required
                       class="field-input" placeholder="••••••••">
            </div>
            <button type="submit" class="btn-submit" style="background:#1B3A5C;color:#fff;">
                Sign In
            </button>
        </form>

        <p style="text-align:center;font-size:14px;color:#6b7280;margin-top:20px;">
            Don't have an account?
            <a href="{{ route('register') }}" style="color:#1B3A5C;font-weight:600;text-decoration:none;">Register here</a>
        </p>

    </div>
</div>

<script>
    if(window.innerWidth >= 768) {
        document.getElementById('left-panel').style.display = 'flex';
    }
</script>
@endif

</body>
</html>
