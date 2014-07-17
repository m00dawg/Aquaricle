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
			DB::raw('username, email, emailReminders, createdAt, 
				updatedAt, mysql.time_zone_name.Name AS timezone'))
			->join('mysql.time_zone_name', 'mysql.time_zone_name.Time_zone_id',
				'=', 'Users.timezoneID')
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
		if(Input::get('emailReminders') == 'Yes' || Input::get('emailReminders') == 'No')
			$user->emailReminders = Input::get('emailReminders');
		$user->timezoneID = Input::get('timezone');
		$user->save();

		$user = User::select(
			DB::raw('username, email, emailReminders, createdAt, updatedAt,
				mysql.time_zone_name.Name AS timezone'))
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
		
		$validator = Validator::make(
			Input::all(),
			array('newPassword' => 'required|min:8|confirmed')
		);
		if ($validator->fails())
		{
			$messages = $validator->messages();
			$status = $messages->first('newPassword');
		}
		elseif(Hash::check(Input::get('currentPassword'), $user->password))
		{
			$user->password = Hash::make(Input::get('newPassword'));
			$user->save();
			return $this->getProfile('Password Updated');
		}
		else
			$status = 'Current Password Does Not Match';
			
		return View::make('user.changepassword', array('status' => $status));

	}

}

?>