<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use App\Models\Post;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () { 
    Post::where('status', 'pending')
        ->where('created_at', '<=', Carbon::now()->subHours(2))
        ->update(['status' => 'approved']);
})->everyMinute();