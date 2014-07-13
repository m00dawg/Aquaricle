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

		$signup = new Signup();
		$signup->username = Input::get('username');
		$signup->password = Hash::make(Input::get('password'));
		$signup->email = Input::get('email');
		$signup->timezoneID = Input::get('timezone');
		$signup->token = str_random(32);
		$signup->save();
		
		$subject = 'Aquaricle New User Registration';
		$signupURL = Config::get('app.url').
			'/signup?username='.$signup->username.'&amp;token='.$signup->token;
		
		Mail::send('email.signup', array('signupURL' => $signupURL),
			function($message) use ($signup, $subject)
			{
		    	$message->to($signup->email, $signup->username)->subject($subject);
			}
		);
	}
}

?>