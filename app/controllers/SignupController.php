<?php


class SignupController extends BaseController
{

	public function getSignup()
	{
		$timezones = DB::table('mysql.time_zone_name')->lists('Name', 'Time_zone_id');
		return View::make('signup')->with('timezones', $timezones);
	}
	
	public function postSignup()
	{
		$validator = Validator::make(
			Input::all(),
			array('username' => 'required|unique:users|unique:signups',
					'email' => 'required|email|unique:Users',
					'password' => 'required|min:8|confirmed',
					'timezone' => 'required')
		);
		
		if ($validator->fails())
			return Redirect::to('signup')
				->withInput(Input::except('password', 'password_confirmation'))
				->withErrors($validator);
		
		
	}
}

?>