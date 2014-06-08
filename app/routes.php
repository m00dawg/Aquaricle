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
Route::when('aquariums/*', 'auth.aquarium');
Route::when('*', 'csrf', array('post', 'put', 'patch'));

/* RESTful Resource Controllers */
Route::resource('aquariums', 'AquariumController');
Route::resource('aquariums.logs', 'AquariumLogsController');
Route::resource('aquariums.equipment', 'EquipmentController');

Route::get('/', function()
{
	//return View::make('index');
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
