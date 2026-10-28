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

    // Inbox: only roles with the "inbox" permission (see config/admin.php > roles)
    Route::middleware('admin.can:inbox')->group(function () {

        // 1. Contact messages ----------------------------------------------------
        Route::resource('contact-messages', 'ContactMessageController')
            ->only(['index', 'show'])
            ->parameters(['contact-messages' => 'id']);
        Route::patch('contact-messages/{id}/status', 'ContactMessageController@updateStatus')->name('contact-messages.status');
        Route::post('contact-messages/{id}/reply', 'ContactMessageController@reply')->name('contact-messages.reply');

        // 2. Quote requests ------------------------------------------------------
        Route::resource('quote-requests', 'QuoteRequestController')
            ->only(['index', 'show'])
            ->parameters(['quote-requests' => 'id']);
        Route::patch('quote-requests/{id}/status', 'QuoteRequestController@updateStatus')->name('quote-requests.status');
        Route::post('quote-requests/{id}/reply', 'QuoteRequestController@reply')->name('quote-requests.reply');
        Route::get('quote-requests/{id}/file', 'QuoteRequestController@file')->name('quote-requests.file');
    });

    // Deleting inbox records: super admins only (the "delete" permission, see config/admin.php > roles)
    Route::middleware('admin.can:delete')->group(function () {
        Route::delete('contact-messages/{id}', 'ContactMessageController@destroy')->name('contact-messages.destroy');
        Route::delete('quote-requests/{id}', 'QuoteRequestController@destroy')->name('quote-requests.destroy');
    });

    // Admin users: super admins only
    Route::middleware('admin.can:users')->group(function () {
        Route::resource('admin-users', 'AdminUserController')
            ->only(['index', 'store', 'edit', 'update', 'destroy'])
            ->parameters(['admin-users' => 'id']);
    });

    // Website content: wording of the home page / footer, home slider, mission panels, inner pages
    Route::middleware('admin.can:content')->group(function () {
        Route::get('landing', 'ContentGroupController@index')->name('landing.index');
        Route::get('landing/{group}', 'ContentGroupController@edit')->name('landing.edit');
        Route::put('landing/{group}', 'ContentGroupController@update')->name('landing.update');
        Route::delete('landing/{group}', 'ContentGroupController@reset')->name('landing.reset');

        // News & events
        Route::resource('news', 'NewsEventController')->except(['show'])->parameters(['news' => 'id']);

        // Services and Products (with pictures)
        Route::resource('services', 'ServiceController')->except(['show'])->parameters(['services' => 'id']);
        Route::resource('products', 'ProductController')->except(['show'])->parameters(['products' => 'id']);

        // Reviews & ratings: all reviews kept here, admin chooses what shows on the website
        Route::patch('reviews/{id}/toggle', 'ReviewController@toggle')->name('reviews.toggle');
        Route::resource('reviews', 'ReviewController')->except(['show'])->parameters(['reviews' => 'id']);
        Route::get('review-invites', 'ReviewInviteController@index')->name('review-invites.index');
        Route::post('review-invites', 'ReviewInviteController@store')->name('review-invites.store');
        Route::post('review-invites/{id}/resend', 'ReviewInviteController@resend')->name('review-invites.resend');
        Route::patch('review-invites/{id}/revoke', 'ReviewInviteController@revoke')->name('review-invites.revoke');
        Route::delete('review-invites/{id}', 'ReviewInviteController@destroy')->name('review-invites.destroy');

        // Machines / Automation page
        Route::patch('machines/{id}/toggle', 'MachineController@toggle')->name('machines.toggle');
        Route::resource('machines', 'MachineController')->except(['show'])->parameters(['machines' => 'id']);

        // Shop Now page products
        Route::patch('shop-products/{id}/toggle', 'ShopProductController@toggle')->name('shop-products.toggle');
        Route::resource('shop-products', 'ShopProductController')->except(['show'])->parameters(['shop-products' => 'id']);

        // Partners (home page logo slider)
        Route::resource('partners', 'PartnerController')->except(['show'])->parameters(['partners' => 'id']);

        Route::get('records/{table}', 'RecordController@index')->name('records.index');
        Route::get('records/{table}/create', 'RecordController@create')->name('records.create');
        Route::post('records/{table}', 'RecordController@store')->name('records.store');
        Route::get('records/{table}/{id}/edit', 'RecordController@edit')->name('records.edit');
        Route::put('records/{table}/{id}', 'RecordController@update')->name('records.update');
        Route::delete('records/{table}/{id}', 'RecordController@destroy')->name('records.destroy');
    });

    // 3+. (Services, Projects, Products ... are added here step by step)
});
