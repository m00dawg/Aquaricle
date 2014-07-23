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
Route::when('life*', 'auth');
Route::when('food*', 'auth');
Route::when('user*', 'auth');
Route::when('aquariums/*/', 'auth.aquarium');
Route::when('*', 'csrf', array('post', 'put', 'patch'));
Route::when('public/aquariums/*', 'aquarium.public');

/* RESTful Resource Controllers */
Route::resource('aquariums', 'AquariumController');

/// Public Aquariums
Route::get('/public/aquariums/{aquariumID}', [
	'before' => 'cache',
	'after' => 'cache',
	'as'	=> 'public.aquariums',
	'uses'	=> 'AquariumController@getPublicAquarium'
]);
	
Route::get('/public/aquariums/{aquariumID}/logs/waterlogs', [
	'before' => 'cache',
	'after' => 'cache',
    'as'     => 'public.aquariums.logs.waterlogs',
    'uses'   => 'AquariumLogsController@getPublicWaterLogs'
]);

/// Aquarium Life Module
Route::get('/aquariums/{aquariumID}/life', [
    'as'     => 'aquariums.life',
    'uses'   => 'AquariumLifeController@index'
]);

Route::get('/aquariums/{aquariumID}/life/create', [
    'as'     => 'aquariums.life.create',
    'uses'   => 'AquariumLifeController@create'
]);

Route::post('/aquariums/{aquariumID}/life/create', [
    'as'     => 'aquariums.life.store',
    'uses'   => 'AquariumLifeController@store'
]);

Route::get('/aquariums/{aquariumID}/life/{lifeID}', [
    'as'     => 'aquariums.life.show',
    'uses'   => 'AquariumLifeController@show'
]);

Route::get('/aquariums/{aquariumID}/life/{lifeID}/edit', [
    'as'     => 'aquariums.life.edit',
    'uses'   => 'AquariumLifeController@edit'
]);
	
Route::post('/aquariums/{aquariumID}/life/{lifeID}/update', [
    'as'     => 'aquariums.life.update',
    'uses'   => 'AquariumLifeController@update'
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

// Water Additives
Route::get('/aquariums/{aquariumID}/wateradditives', [
    'as'     => 'aquariums.wateradditives',
    'uses'   => 'WaterAdditivesController@index'
]);	
	
Route::get('/aquariums/{aquariumID}/wateradditives/{waterAdditiveID}', [
    'as'     => 'aquariums.wateradditives.show',
    'uses'   => 'WaterAdditivesController@getWaterAdditive'
]);	

// Food
Route::get('/aquariums/{aquariumID}/feedings/', [
    'as'     => 'aquariums.feedings',
    'uses'   => 'FoodController@getFeedings'
]);

Route::get('/food/', [
    'as'     => 'food',
    'uses'   => 'FoodController@index'
]);

Route::get('/food/create', [
    'as'     => 'food.create',
    'uses'   => 'FoodController@create'
]);
	
Route::post('/food/create', [
    'as'     => 'food.create',
    'uses'   => 'FoodController@store'
]);

Route::get('/food/{foodID}/edit', [
    'as'     => 'food.edit',
    'uses'   => 'FoodController@edit'
]);	

Route::post('/food/{foodID}/edit', [
    'as'     => 'food.edit',
    'uses'   => 'FoodController@update'
]);	

/// Global Life Module
Route::get('/life/', [
    'as'     => 'life',
    'uses'   => 'LifeController@index'
]);
	
Route::get('/life/create', [
    'as'     => 'life.create',
    'uses'   => 'LifeController@create'
]);

Route::post('/life/create', [
    'as'     => 'life.create',
    'uses'   => 'LifeController@store'
]);
	
Route::get('/life/{lifeID}', [
    'as'     => 'life.show',
    'uses'   => 'LifeController@show'
]);
	
Route::get('/life/{lifeID}/edit', [
    'as'     => 'life.edit',
    'uses'   => 'LifeController@edit'
]);
	
Route::post('/life/{lifeID}/edit', [
    'as'     => 'life.update',
    'uses'   => 'LifeController@update'
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
	return View::make('index');
});


Route::get('login', function()
{
	return View::make('login');
});

Route::get('logout', function()
{
	Auth::logout();
	return Redirect::to('/');
});

Route::post('login', function()
{
	//true = Remember Me
	if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')), true))
		return Redirect::intended('aquariums');
	return View::make('login')->with('status', 'Login Failed');
});

Route::get('/signup', [
    'as'     => 'signup',
    'uses'   => 'SignupController@getSignup'
]);
	
Route::post('/signup', [
    'as'     => 'signup',
    'uses'   => 'SignupController@postSignup'
]);
	
Route::get('/signup/validate', [
    'as'     => 'signup.validate',
    'uses'   => 'SignupController@getValidate'
]);
	

/*
Route::get('users', function()
{
	$users = User::all();
    return View::make('users')->with('users', $users);
});
*/

?>
