<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $affectedRows = DB::table('subscription_users')
        ->where('ends_at', '<=', now())
        ->where('status', 'active')
        ->update(['status' => 'expired']);
    Log::info("Updated $affectedRows subscription(s) to expired status.");
})->everyMinute();

Schedule::call(function () {
    $affectedRows = DB::table('elections')
        ->where('end_date', '<=', now())
        ->where('is_open', 1)
        ->update(['is_open' => 0]);
    Log::info("Closed $affectedRows election(s) that have reached their end date.");
})->everyMinute();
