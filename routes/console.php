<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes / Scheduler
|--------------------------------------------------------------------------
| Firebase → MySQL auto-sync runs every 5 minutes.
| Make sure your server cron is set up:
|   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
*/

Schedule::command('firebase:sync')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('Scheduled Firebase sync failed.');
    })
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('Scheduled Firebase sync succeeded.');
    });
