<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$mystring =  $_SERVER['REQUEST_URI'] ?? '';
$findme   = 'index.php';
$pos = strpos($mystring, $findme);
if ($pos !== false) {
	$main_path = Config::get('app.url');
	echo '<script>window.location= "' . $main_path . '"</script>';
}



Route::get('/', 'WelcomeController@index');
Route::get('/machines.html', function () {
	return view('pages.machines', [
		'title'       => 'Machines & Manufacturing | SASA Packaging',
		'description' => 'The machinery and production capabilities behind our packaging solutions.',
	]);
});
// Public forms. Each one is limited to 10 submissions per minute per visitor (stops spam / flooding).
// Contact + Quote also need a passed Cloudflare Turnstile check (middleware "captcha:<form>", see config/captcha.php).
// Order matters: the throttle runs first, so a flood cannot become a flood of calls to Cloudflare.
Route::post('/common/contact_process', 'Site\ContactController@store')->middleware(['throttle:10,1', 'captcha:contact']);
Route::post('/careers/apply', 'CareerController@apply')->middleware('throttle:10,1');
Route::post('/send_quote', 'Site\QuoteController@store')->middleware(['throttle:10,1', 'captcha:quote']);

// SECURITY: the old routes /common/processenq, /common/commentsubmit, /common/register_process and
// /save_career were removed. Their code no longer exists (they only caused errors), and /save_career
// accepted ANY file type into a public folder, which would let an attacker upload a PHP file and take
// over the server. The Careers page uses /careers/apply, which is safe.

// ADMIN ROUTES REMOVED - admin panel is being rebuilt from scratch.
// New admin routes will live in routes/admin.php

// Events page (exhibitions): latest event on top, earlier ones below. Address from config/news.php
Route::get(ltrim(config('news.events_url'), '/'), 'Site\EventsController@index');

// Shop Now: Pinterest-style product grid + product page. Addresses come from config/shop.php.
// (Placed before the catch-all "{slug}" route below, otherwise the CMS lookup would answer first.)
Route::get(ltrim(config('shop.url'), '/'), 'Site\ShopController@index');
Route::get(trim(config('shop.product_url'), '/') . '/{slug}', 'Site\ShopController@show')->where('slug', '[A-Za-z0-9\-]+');

// Private feedback link for invited partners (one use per link). Throttled against guessing / flooding.
Route::get('feedback/{token}', 'Site\FeedbackController@show')->where('token', '[A-Za-z0-9]{20,64}')->middleware('throttle:30,1');
Route::post('feedback/{token}', 'Site\FeedbackController@store')->where('token', '[A-Za-z0-9]{20,64}')->middleware('throttle:10,1');

// Anything else is looked up in the CMS (BaseController shows a proper 404 page when nothing matches).
Route::get('blog/{slug}', array('uses' => 'BaseController@route'));
Route::get('{slug}', array('uses' => 'BaseController@route'));
