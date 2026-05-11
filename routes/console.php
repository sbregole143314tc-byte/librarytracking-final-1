<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\BorrowingService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Mark overdue borrowings daily at midnight
Schedule::call(function () {
    $count = app(BorrowingService::class)->markOverdue();
    logger("BookTrack: Marked {$count} borrowings as overdue.");
})->dailyAt('00:05')->name('mark-overdue');
