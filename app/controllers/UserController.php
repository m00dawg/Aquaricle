<?php

class UserController extends BaseController
{	
	
	public function getIndex()
	{
		return Redirect::to('user/profile');
	}
	
    public function getProfile($status = null)
    {
		$user = User::select(
			DB::raw('username, email, createdAt, updatedAt, mysql.time_zone_name.Name AS timezone'))
			->join('mysql.time_zone_name', 'mysql.time_zone_name.Time_zone_id',
				'=', 'Users.timeZoneID')
			->where('userID', '=', Auth::id())
			->first();
        return View::make('user.profile', array('user' => $user, 'status' => $status));
    }

	public function updateProfile()
	{
		$user = Auth::user();
		$timezones = DB::table('mysql.time_zone_name')->lists('Name', 'Time_zone_id');

        return View::make('user.editprofile', array('user' => $user, 'timezones' => $timezones));
	}

	public function storeProfile()
	{
		DB::beginTransaction();
		$user = Auth::user();
		$user->email = Input::get('email');
		$user->timezoneID = Input::get('timezone');
		$user->save();

		$user = User::select(
			DB::raw('username, email, createdAt, updatedAt, mysql.time_zone_name.Name AS timezone'))
			->join('mysql.time_zone_name', 'mysql.time_zone_name.Time_zone_id',
				'=', 'Users.timeZoneID')
			->where('userID', '=', Auth::id())
			->first();
				
		DB::commit();
        return View::make('user.profile', array('user' => $user, 'status' => 'Profile Updated'));
	}

	public function getChangePassword()
	{
        return View::make('user.changepassword');
	}
	
	public function postChangePassword()
	{
		$user = Auth::user();
		$status = '';
		if(Hash::check(Input::get('currentPassword'), $user->password))
		{
			if(Input::get('newPassword1') == Input::get('newPassword2'))
			{
				$user->password = Hash::make(Input::get('newPassword1'));
				$user->save();
				return $this->getProfile('Password Updated');
			}
			else
			{
				$status = 'New Passwords Do Not Match';
			}
		}
		else
			$status = 'Current Password Does Not Match';
			
		return View::make('user.changepassword', array('status' => $status));

			
		//$user = Auth::user();
		/*
		if (Hash::check('secret', $hashedPassword))
		{
		    // The passwords match...
		}
		
		return Hash::make('secret');
		*/
		
	}

}

?>