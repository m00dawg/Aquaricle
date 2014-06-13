<?php

class UserController extends BaseController
{	
	
	public function getIndex()
	{
		return Redirect::to('user/profile');
	}
	
    public function getProfile()
    {
		$user = Auth::user();

        return View::make('user.profile', array('user' => $user));
    }

	public function updateProfile()
	{
		$user = Auth::user();

        return View::make('user.editprofile', array('user' => $user));
	}

	public function storeProfile()
	{
		$user = Auth::user();
		$user->email = Input::get('email');
		$user->save();
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
				$status = 'Password Updated';
		        return View::make('user.profile', array('user' => $user, 'status' => $status));
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