<?php


class SignupController extends BaseController
{

	public function getSignup()
	{
		$timezones = DB::table('mysql.time_zone_name')->lists('Name', 'Time_zone_id');
		return View::make('signup/index')->with('timezones', $timezones);
	}
	
	public function postSignup()
	{
		$validator = Validator::make(
			Input::all(),
			array('username' => 'required|unique:Users|unique:Signups',
					'email' => 'required|email|unique:Users|unique:Signups',
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
			'/signup/validate?username='.Input::get('username').'&amp;token='.$signup->token;
		Mail::send('email.signup', array('signupURL' => $signupURL),
			function($message) use ($signup, $subject)
			{
		    	$message->to($signup->email, Input::get('username'))->subject($subject);
			}
		);
		
		return View::make('signup/instructions');
	}
	
	public function getValidate()
	{
		DB::beginTransaction();
		$signup = Signup::where('username', '=', Input::get('username'))
			->where('token', '=', Input::get('token'))
			->first();
		if($signup)
		{
			$user = new User();
			$user->username = $signup->username;
			$user->password = $signup->password;
			$user->timezoneID = $signup->timezoneID;
			$user->email = $signup->email;
			$user->save();
			$signup->delete();
			DB::commit();
			return View::make('login')
				->with('status', 'Registration Successful! You May Now Login!');;
		}
		else
		{
			DB::rollback();
			return Redirect::to('/');
		}		
	}
}

?>