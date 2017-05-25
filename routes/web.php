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
/*
Route::get('/', function () {
    return view('welcome');
});
*/
// Auth routes
//Auth::routes();
// home page route
Route::get('/','PagesController@index')->name('home');
// test route
Route::get('test','TestController@index')->middleware(['auth', 'throttle']);
// Widget routes
Route::get('widget/create', 'WidgetController@create')->name('widget.create');

Route::get('widget/{widget}-{slug?}', 'WidgetController@show')->name('widget.show');

Route::resource('widget', 'WidgetController', ['except' => ['show', 'create']]);

//admin
Route::get('/admin', 'AdminController@index')->name('admin');

//terms of service
Route::get('terms-of-service', 'PagesController@terms');

//privacy
Route::get('privacy', 'PagesController@privacy');

//facebook login
Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');

Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

//Route::get('/home', 'HomeController@index')->name('home');
//Authentication routes
Route::get('login', 'Auth\AuthController@showLoginForm')->name('login');

Route::post('login', 'Auth\AuthController@login');

Route::post('logout', 'Auth\AuthController@logout')->name('logout');

//Password Reset Routes
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::post('password/reset', 'Auth\ResetPasswordController@reset');

//Privacy route
Route::get('privacy', 'PagesController@privacy');

//Registration Route
Route::get('register', 'Auth\AuthController@showRegistrationForm')->name('register');

Route::post('register', 'Auth\AuthController@register');

//Profile
Route::get('show-profile', 'ProfileController@showProfileToUser')->name('show-profile');

Route::get('determine-profile-route', 'ProfileController@determineProfileRoute');

Route::resource('profile', 'ProfileController');

//User
Route::resource('user', 'UserController');

Route::get('settings', 'SettingsController@edit');

Route::post('settings', 'SettingsController@update')->name('user-update');

//marketing-image
Route::resource('marketing-image', 'MarketingImageController');

//api
Route::get('api/widget-data', 'ApiController@widgetData');
Route::get('api/marketing-image-data', 'ApiController@marketingImageData');

//Chat routes
Route::get('/chat-messages', 'ChatController@getMessages')->middleware('auth');
Route::post('/chat-messages', 'ChatController@postMessage')->middleware('auth');
Route::get('/chat', 'ChatController@index')->middleware('auth');
Route::get('/username', 'UsernameController@show')->middleware('auth');

//Category
Route::get('api/category-data', 'ApiController@categoryData');
Route::get('api/subcategory-data', 'ApiController@subcategoryData');
Route::resource('category', 'CategoryController');
Route::resource('subcategory', 'SubcategoryController');