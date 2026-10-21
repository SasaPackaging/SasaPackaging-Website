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
Route::post('/common/contact_process', 'Site\ContactController@store')->middleware('throttle:10,1');
Route::post('/common/processenq', 'CommonController@processenq');
Route::post('/common/commentsubmit', 'CommonController@commentsubmit');

Route::post('/common/register_process', 'CommonController@register_process');
Route::post('/careers/apply', 'CareerController@apply')->middleware('throttle:10,1');
Route::post('/save_career', 'CommonController@save_career');
Route::post('/send_quote', 'Site\QuoteController@store')->middleware('throttle:10,1');

// ADMIN ROUTES REMOVED - admin panel is being rebuilt from scratch.
// New admin routes will live in routes/admin.php

// Events page (exhibitions): latest event on top, earlier ones below. Address from config/news.php
Route::get(ltrim(config('news.events_url'), '/'), 'Site\EventsController@index');

Route::get('blog/{slug}', array('uses' => 'BaseController@route'));
Route::get('{slug}', array('uses' => 'BaseController@route'));
