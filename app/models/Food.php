<?php

class Food extends BaseModel {

	protected $table = 'Food';
	protected $guarded = array('foodID', 'userID');
	public $primaryKey = 'foodID';
	public $timestamps = false;

}