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

Route::when('aquarium*', 'auth');

Route::get('/', function()
{
	return View::make('index');
});

Route::get('login', function()
{
	return View::make('login');
});

Route::post('login', function()
{
	//true = Remember Me
	//if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')), true))
	/*
	if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password'))))
		return Redirect::intended('aquariums');
	return View::make('login')->with('status', 'Login Failed');
	*/
	
	//Stub so I can handle authentication later
	Auth::loginUsingId(1);
	return Redirect::intended('aquariums');
});

Route::get('users', function()
{
	$users = User::all();
    return View::make('users')->with('users', $users);
});

/* List a user's aquariums */
//Route::get('aquariums', 'AquariumController@getAquariums');
Route::controller('aquariums', 'AquariumController');

/*

Route::get('aquarium/{aquariumID?}', function($aquariumID = null)
{
	if ($aquariumID == null)
		return Redirect::intended('aquariums');

	$aquarium = Aquarium::find($aquariumID);

	if($aquarium->measurementUnits = 'Metric')
	{
		$volumeUnits = 'L';
		$lengthUnits = 'cm';
	}
	else
	{
		$volumeUnits = 'Gal';
		$lengthUnits = 'inches';
	}

	return View::make('aquarium')
		->with('aquarium', $aquarium)
		->with('volumeUnits', $volumeUnits)
		->with('lengthUnits', $lengthUnits);
});
*/
?>
