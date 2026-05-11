<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — LibraryTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;}
        input{font-family:'DM Sans',sans-serif;}
        .field-label{display:block;font-size:13.5px;font-weight:500;color:#374151;margin-bottom:6px;}
        .field-input{display:block;width:100%;border:1.5px solid #d1d5db;border-radius:10px;padding:11px 14px;font-size:14px;outline:none;transition:border 0.15s,box-shadow 0.15s;}
        .field-input:focus{border-color:#2C5F8A;box-shadow:0 0 0 3px rgba(44,95,138,0.15);}
    </style>
</head>
<body>

{{-- Left panel --}}
<div style="display:none;background:#1B3A5C;width:40%;align-items:center;justify-content:center;padding:60px 48px;flex-direction:column;" id="left-panel">
    <div style="max-width:300px;text-align:center;">
        <div style="width:64px;height:64px;border-radius:16px;background:#D4A853;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <h1 style="font-family:'Playfair Display',serif;font-size:32px;color:#fff;font-weight:700;margin-bottom:10px;">Join LibraryTrack</h1>
        <p style="color:#93c5fd;font-size:14px;line-height:1.6;margin-bottom:28px;">Create your free student account and start borrowing today.</p>
        <div style="background:rgba(255,255,255,0.07);border-radius:12px;padding:18px 20px;text-align:left;">
            <p style="font-size:11.5px;font-weight:600;color:#bfdbfe;text-transform:uppercase;letter-spacing:0.07em;margin-bottom:12px;">What you get</p>
            @foreach(['Borrow up to 4 books at once','14-day loan period per book','2 renewals per borrowing','Borrowing history & tracking','Overdue reminders'] as $b)
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                <div style="width:18px;height:18px;border-radius:50%;background:#D4A853;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="10" height="10" fill="white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <span style="color:#e0eeff;font-size:13.5px;">{{ $b }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Right panel --}}
<div style="flex:1;background:#f5f7fa;display:flex;align-items:center;justify-content:center;padding:40px 24px;">
    <div style="width:100%;max-width:420px;">
        <div style="background:#fff;border-radius:16px;border:1px solid #e8edf5;box-shadow:0 2px 12px rgba(27,58,92,0.07);padding:36px;">

            <div style="display:flex;align-items:center;gap:10px;margin-bottom:28px;">
                <div style="width:34px;height:34px;border-radius:8px;background:#1B3A5C;display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span style="font-family:'Playfair Display',serif;font-size:20px;color:#111827;font-weight:600;">LibraryTrack</span>
            </div>

            <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:#111827;margin:0 0 4px;font-weight:700;">Create your account</h2>
            <p style="font-size:13.5px;color:#6b7280;margin:0 0 24px;">Register as a library Student — free & instant</p>

            @if($errors->any())
            <div style="padding:12px 14px;border-radius:10px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:13.5px;margin-bottom:18px;">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" style="display:flex;flex-direction:column;gap:14px;">
                @csrf
                <div>
                    <label class="field-label">Full Name <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="field-input" placeholder="e.g. Maria Santos">
                </div>
                <div>
                    <label class="field-label">Email Address <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="field-input" placeholder="you@example.com">
                </div>
                <div>
                    <label class="field-label">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="field-input" placeholder="09XXXXXXXXX">
                </div>
                <div>
                    <label class="field-label">Password <span style="color:#ef4444">*</span></label>
                    <input type="password" name="password" required class="field-input" placeholder="Min. 8 characters">
                </div>
                <div>
                    <label class="field-label">Confirm Password <span style="color:#ef4444">*</span></label>
                    <input type="password" name="password_confirmation" required class="field-input">
                </div>
                <button type="submit" style="width:100%;padding:12px;background:#1B3A5C;color:#fff;border:none;border-radius:10px;font-size:14.5px;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;margin-top:4px;transition:opacity 0.15s;">
                    Create Student Account
                </button>
            </form>

            <p style="text-align:center;font-size:13.5px;color:#6b7280;margin-top:20px;">
                Already have an account?
                <a href="{{ route('login') }}" style="color:#1B3A5C;font-weight:600;text-decoration:none;">Sign in</a>
            </p>
        </div>
    </div>
</div>

<script>if(window.innerWidth>=768){document.getElementById('left-panel').style.display='flex';}</script>
</body>
</html>
