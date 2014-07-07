<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('login');
	
	$timezone = DB::table('Users')
		->select(DB::raw('time_zone_name.Name AS timezone'))
		->join('mysql.time_zone_name', 'mysql.time_zone_name.Time_zone_id',
			'=', 'Users.timezoneID')
		->where('userID', '=', Auth::id())
		->first();
	DB::statement("SET SESSION time_zone = '".$timezone->timezone."'");

});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

// Check to make sure the user is accessing only their aquariums
Route::filter('auth.aquarium', function()
{
	if(!(DB::table('Aquariums')
		->where('aquariumID', '=', Route::input('aquariumID'))
		->where('userID', '=', Auth::id())
		->first()))
			return Redirect::to('aquariums');
});

// Check to see if aquarium is public
Route::filter('aquarium.public', function()
{
	$aquarium = Aquarium::where('aquariumID', '=', Route::input('aquariumID'))
		->first();
	if(!$aquarium)
		return Redirect::to('login');
	if($aquarium->visibility != 'Public')
		return Redirect::to('login');
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});



