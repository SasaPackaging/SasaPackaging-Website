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
		echo '<script>window.location= "'.$main_path.'"</script>';
	}
	
	

Route::get('/', 'WelcomeController@index');

Route::post('/common/contact_process', 'CommonController@contact_process');
Route::post('/common/processenq', 'CommonController@processenq');
Route::post('/common/commentsubmit', 'CommonController@commentsubmit');

Route::post('/common/register_process', 'CommonController@register_process');
Route::post('/save_career', 'CommonController@save_career');
Route::post('/send_quote', 'CommonController@send_quote');

Route::group(array('namespace' => 'Admin','prefix' => 'admin'), function() {
	Route::get('/', 'AdminController@index');
	Route::post('/login', 'AdminController@login');
	Route::get('/logout', 'AdminController@logout');
	Route::get('/dashboard', 'AdminController@dashboard');
	Route::get('/profile', 'AdminController@profile');
	Route::post('/update_profile', 'AdminController@update_profile');
	
	Route::get('/settings', 'SettingsController@index');
	Route::post('/settings/update_setting', 'SettingsController@update_setting');
	//------------------------------------------------------------------------------------
	Route::get('/banners', 'BannersController@index');
	Route::get('/banners/create', 'BannersController@create');
	Route::post('/banners/create_save', 'BannersController@create_save');
	Route::get('/banners/edit/{id}', 'BannersController@edit');
	Route::post('/banners/edit_save', 'BannersController@edit_save');
	Route::get('/banners/delete/{id}', 'BannersController@delete');
	Route::post('/banners/status', 'BannersController@status');
	//------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------
	Route::get('/category', 'CategoryController@index');
	Route::get('/category/index', 'CategoryController@index');
	Route::get('/category/create', 'CategoryController@create');
	Route::post('/category/create_save', 'CategoryController@create_save');
	Route::get('/category/edit/{id}', 'CategoryController@edit');
	Route::post('/category/edit_save', 'CategoryController@edit_save');
	Route::get('/category/delete/{id}', 'CategoryController@delete');
	Route::post('/category/status', 'CategoryController@status');
	Route::post('/category/subcategory', 'CategoryController@subcategory');
	Route::post('/category/statusfeatured', 'CategoryController@statusfeatured');
	
	//------------------------------------------------------------------------------------
	
	
	
	
	//------------------------------------------------------------------------------------
	
	
	
	//------------------------------------------------------------------------------------
	Route::get('/pages', 'PagesController@index');
	Route::get('/pages/create', 'PagesController@create');
	Route::post('/pages/create_save', 'PagesController@create_save');
	Route::get('/pages/edit/{id}', 'PagesController@edit');
	Route::post('/pages/edit_save', 'PagesController@edit_save');
	Route::get('/pages/delete/{id}', 'PagesController@delete');
	Route::post('/pages/status', 'PagesController@status');
	Route::post('/pages/statusfooter', 'PagesController@statusfooter');
	Route::post('/pages/statusheader', 'PagesController@statusheader');
	Route::post('/pages/statusquick', 'PagesController@statusquick');
	//------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------
	Route::get('/services', 'ServicesController@index');
	Route::get('/services/create', 'ServicesController@create');
	Route::post('/services/create_save', 'ServicesController@create_save');
	Route::get('/services/edit/{id}', 'ServicesController@edit');
	Route::post('/services/edit_save', 'ServicesController@edit_save');
	Route::get('/services/delete/{id}', 'ServicesController@delete');
	Route::post('/services/status', 'ServicesController@status');
	Route::post('/services/statusfooter', 'ServicesController@statusfooter');
	Route::post('/services/statusfeatured', 'ServicesController@statusfeatured');
	
	//------------------------------------------------------------------------------------
	
	//------------------------------------------------------------------------------------
	Route::get('/products', 'ProductsController@index');
	Route::get('/products/create', 'ProductsController@create');
	Route::post('/products/create_save', 'ProductsController@create_save');
	Route::get('/products/edit/{id}', 'ProductsController@edit');
	Route::post('/products/edit_save', 'ProductsController@edit_save');
	Route::get('/products/delete/{id}', 'ProductsController@delete');
	Route::post('/products/status', 'ProductsController@status');
	
	Route::post('/products/statusfeatured', 'ProductsController@statusfeatured');
	
	
	Route::get('/products/images/{id}', 'ProductsController@images');
	Route::get('/products/createimage/{id}', 'ProductsController@createimage');
	Route::post('/products/createimage_save', 'ProductsController@createimage_save');
	Route::get('/products/imagedelete/{id}/{sid}', 'ProductsController@imagedelete');
	
	
	Route::get('/products/ptypes/{id}', 'ProductsController@ptypes');
	Route::get('/products/createtypes/{id}', 'ProductsController@createtypes');
	Route::post('/products/createtypes_save', 'ProductsController@createtypes_save');
	Route::get('/products/typesdelete/{id}/{sid}', 'ProductsController@typesdelete');
	Route::post('/products/statustypes', 'ProductsController@statustypes');
	//------------------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------------------
	Route::get('/members', 'MembersController@index');
	Route::get('/members/index', 'MembersController@index');
	Route::get('/members/create', 'MembersController@create');
	Route::post('/members/create_save', 'MembersController@create_save');
	Route::get('/members/edit/{id}', 'MembersController@edit');
	Route::post('/members/edit_save', 'MembersController@edit_save');
	Route::get('/members/delete/{id}', 'MembersController@delete');
	Route::post('/members/status', 'MembersController@status');
	
	//------------------------------------------------------------------------------------

	Route::get('/projects', 'ProjectsController@index');

	Route::get('/projects/create', 'ProjectsController@create');

	Route::post('/projects/create_save', 'ProjectsController@create_save');

	Route::get('/projects/edit/{id}', 'ProjectsController@edit');

	Route::post('/projects/edit_save', 'ProjectsController@edit_save');

	Route::get('/projects/delete/{id}', 'ProjectsController@delete');

	Route::post('/projects/status', 'ProjectsController@status');

	

	Route::post('/projects/statusfeatured', 'ProjectsController@statusfeatured');

	//------------------------------------------------------------------------------------

	Route::get('/quotes', 'QuotesController@index');
	Route::get('/quotes/detail/{id}', 'QuotesController@detail');
	Route::get('/quotes/delete/{id}', 'QuotesController@delete');

	//------------------------------------------------------------------------------------
	
	
	
	
});
Route::get('blog/{slug}', array('uses' => 'BaseController@route'));
Route::get('{slug}', array('uses' => 'BaseController@route'));