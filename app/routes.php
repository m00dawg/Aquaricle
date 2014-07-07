<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/* Some Magical Route Filters */
Route::when('aquarium*', 'auth');
Route::when('user*', 'auth');
Route::when('aquariums/*/', 'auth.aquarium');
Route::when('*', 'csrf', array('post', 'put', 'patch'));
Route::when('public/aquariums/*', 'aquarium.public');

/* RESTful Resource Controllers */
Route::resource('aquariums', 'AquariumController');

/// Public Aquariums
Route::get('/public/aquariums/{aquariumID}', [
	'as'	=> 'public.aquariums',
	'uses'	=> 'AquariumController@getPublicAquarium'
]);
	
Route::get('/public/aquariums/{aquariumID}/logs/waterlogs', [
    'as'     => 'public.aquariums.logs.waterlogs',
    'uses'   => 'AquariumLogsController@getPublicWaterLogs'
]);

	
/// Logs

// Favorites
Route::get('/aquariums/{aquariumID}/logs/favorites', [
    'as'     => 'aquariums.logs.favorites',
    'uses'   => 'AquariumLogsController@getFavorites'
]);
Route::post('/aquariums/{aquariumID}/logs/favorites', [
    'as'     => 'aquariums.logs.favorites',
    'uses'   => 'AquariumLogsController@storeFavorite'
]);

// Water Logs
Route::get('/aquariums/{aquariumID}/logs/waterlogs', [
    'as'     => 'aquariums.logs.waterlogs',
    'uses'   => 'AquariumLogsController@getWaterLogs'
]);

// Feedings
Route::get('/aquariums/{aquariumID}/logs/feedings', [
    'as'     => 'aquariums.logs.feedings',
    'uses'   => 'AquariumLogsController@getFeedings'
]);

// Water Additives
Route::get('/aquariums/{aquariumID}/wateradditives', [
    'as'     => 'aquariums.wateradditives',
    'uses'   => 'WaterAdditivesController@index'
]);	
	
Route::get('/aquariums/{aquariumID}/wateradditives/{waterAdditiveID}', [
    'as'     => 'aquariums.wateradditives.show',
    'uses'   => 'WaterAdditivesController@getWaterAdditive'
]);	
	

Route::resource('aquariums.logs', 'AquariumLogsController');



Route::resource('aquariums.equipment', 'EquipmentController');

// User Interactions
// Get Logged In User's Profile
Route::get('/user/profile', [
    'as'     => 'user.profile',
    'uses'   => 'UserController@getProfile'
]);
	
// Update Logged In User's Profile
Route::get('/user/editprofile', [
    'as'     => 'user.editprofile',
    'uses'   => 'UserController@updateProfile'
]);
	
Route::post('/user/editprofile', [
    'as'     => 'user.editprofile',
    'uses'   => 'UserController@storeProfile'
]);

// Update Logged In User's Password
Route::get('/user/password', [
    'as'     => 'user.password',
    'uses'   => 'UserController@getChangePassword'
]);
Route::post('/user/password', [
    'as'     => 'user.password',
    'uses'   => 'UserController@postChangePassword'
]);

Route::get('/', function()
{
	//return View::make('index');
	if(Auth::user())
		return Redirect::to('aquariums');
	return Redirect::to('login');
});

/*
Route::get('password', function()
{
	return Hash::make('secret');
});
*/

Route::get('login', function()
{
	return View::make('login');
});

Route::get('logout', function()
{
	Auth::logout();
	return Redirect::to('login');
});

Route::post('login', function()
{
	//true = Remember Me
	if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')), true))
//	if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password'))))
		return Redirect::intended('aquariums');
	return View::make('login')->with('status', 'Login Failed');
	
	//Stub so I can handle authentication later
	//Auth::loginUsingId(1);
	//return Redirect::intended('aquariums');
});

/*
Route::get('users', function()
{
	$users = User::all();
    return View::make('users')->with('users', $users);
});
*/

?>
