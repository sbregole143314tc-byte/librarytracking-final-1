<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserPortalController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('dashboard')
            : redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

// Public Auth (Users)
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login',   [AuthenticatedSessionController::class, 'store'])->name('login.post');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register',[RegisteredUserController::class, 'store'])->name('register.post');
    Route::get('/admin/login',  [AuthenticatedSessionController::class, 'adminCreate'])->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'adminStore'])->name('admin.login.post');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/reports',   [DashboardController::class, 'reports'])->name('reports');
    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class);
    Route::post('/members/{member}/renew', [MemberController::class, 'renewMembership'])->name('members.renew');
    Route::get('/admin/borrowings',                     [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/admin/borrowings/create',              [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/admin/borrowings',                    [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/admin/borrowings/overdue',             [BorrowingController::class, 'overdue'])->name('borrowings.overdue');
    Route::get('/admin/borrowings/borrowed',            [BorrowingController::class, 'borrowed'])->name('borrowings.borrowed');
    Route::get('/admin/borrowings/available',           [BorrowingController::class, 'available'])->name('borrowings.available');
    Route::get('/admin/borrowings/{borrowing}',         [BorrowingController::class, 'show'])->name('borrowings.show');
    Route::get('/admin/borrowings/{borrowing}/return',  [BorrowingController::class, 'returnBook'])->name('borrowings.return');
    Route::post('/admin/borrowings/{borrowing}/return', [BorrowingController::class, 'processReturn'])->name('borrowings.process-return');
    Route::post('/admin/borrowings/{borrowing}/renew',  [BorrowingController::class, 'renew'])->name('borrowings.renew');
    Route::post('/admin/borrowings/{borrowing}/pay-fine',[BorrowingController::class, 'payFine'])->name('borrowings.pay-fine');
});

// User Portal Routes
Route::middleware(['auth', 'role:user'])->prefix('my')->name('user.')->group(function () {
    Route::get('/dashboard', [UserPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/books',     [UserPortalController::class, 'myBooks'])->name('books');
    Route::get('/overdue',   [UserPortalController::class, 'overdue'])->name('overdue');
    Route::get('/available', [UserPortalController::class, 'availableBooks'])->name('available');
    Route::get('/history',   [UserPortalController::class, 'history'])->name('history');
});
