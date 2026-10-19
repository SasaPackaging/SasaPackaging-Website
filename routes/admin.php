<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin panel routes
|--------------------------------------------------------------------------
| Loaded by RouteServiceProvider with:
|   URL prefix      -> config('admin.prefix')      (default: /admin)
|   route names     -> start with "admin."
|   controllers     -> App\Http\Controllers\Admin
| One block per feature. To add a feature, add a block here and a menu entry in config/admin.php.
*/

// ---- Login (only for people who are NOT logged in) -------------------------
Route::middleware('admin.guest')->group(function () {
    Route::get('login', 'AuthController@showLogin')->name('login');
    Route::post('login', 'AuthController@login')->middleware('throttle:6,1')->name('login.submit');
});

// ---- Everything below needs an admin login ---------------------------------
Route::middleware('admin.auth')->group(function () {

    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::get('/', 'DashboardController@index')->name('dashboard');

    // 1. Contact messages ----------------------------------------------------
    Route::resource('contact-messages', 'ContactMessageController')
        ->only(['index', 'show', 'destroy'])
        ->parameters(['contact-messages' => 'id']);
    Route::patch('contact-messages/{id}/status', 'ContactMessageController@updateStatus')->name('contact-messages.status');
    Route::post('contact-messages/{id}/reply', 'ContactMessageController@reply')->name('contact-messages.reply');

    // 2. Quote requests ------------------------------------------------------
    Route::resource('quote-requests', 'QuoteRequestController')
        ->only(['index', 'show', 'destroy'])
        ->parameters(['quote-requests' => 'id']);
    Route::patch('quote-requests/{id}/status', 'QuoteRequestController@updateStatus')->name('quote-requests.status');
    Route::post('quote-requests/{id}/reply', 'QuoteRequestController@reply')->name('quote-requests.reply');
    Route::get('quote-requests/{id}/file', 'QuoteRequestController@file')->name('quote-requests.file');

    // 3+. (Services, Projects, Products ... are added here step by step)
});
