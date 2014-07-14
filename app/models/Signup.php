<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Signup extends BaseModel
{
	protected $table = 'Signups';
	protected $primaryKey = 'username';
	protected $guarded = array('username', 'email', 'token');
	public $timestamps = true;
}