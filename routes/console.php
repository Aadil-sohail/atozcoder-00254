<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('ebay:sync-orders')->everyFiveMinutes()->withoutOverlapping();

//A return is only imported once eBay marks it delivered, refunded, or closed with 
// money paid back. An open return request won't show until it reaches that point.
Schedule::command('ebay:sync-returns')->everyFiveMinutes()->withoutOverlapping();
