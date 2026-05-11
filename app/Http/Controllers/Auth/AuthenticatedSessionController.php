<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    // User login page
    public function create(): View
    {
        return view('auth.login', ['isAdmin' => false]);
    }

    // User login submit
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = auth()->user();

        if ($user->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Please use the Admin Login portal.']);
        }

        if (! $user->member_id) {
            Auth::logout();
            return back()->withErrors(['email' => 'No member account linked. Please contact the library.']);
        }

        return redirect()->intended(route('user.dashboard'));
    }

    // Admin login page
    public function adminCreate(): View
    {
        return view('auth.login', ['isAdmin' => true]);
    }

    // Admin login submit
    public function adminStore(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = auth()->user();

        if (! $user->isAdmin()) {
            Auth::logout();
            return back()->withErrors(['email' => 'This account does not have admin privileges.']);
        }

        return redirect()->intended(route('dashboard'));
    }

    // Logout
    public function destroy(Request $request): RedirectResponse
    {
        $wasAdmin = auth()->user()?->isAdmin();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $wasAdmin
            ? redirect()->route('admin.login')
            : redirect()->route('login');
    }
}
