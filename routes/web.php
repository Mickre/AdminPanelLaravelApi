<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/set/storage', function () {
    Artisan::call('storage:link');
    return 'Storage!';
});

Route::get('/set/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'Clear!';
});

Route::domain('admin.{domain}.{tld}')->group(function () {
    Route::any('/{any}', function () {
        return view('admin');
    })->where('any', '^(?!api).*$');
});

Route::domain('www.admin.{domain}.{tld}')->group(function () {
    Route::any('/{any}', function () {
        return view('admin');
    })->where('any', '^(?!api).*$');
});
