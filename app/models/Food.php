<?php

class Food extends BaseModel {

	protected $table = 'Food';
	protected $guarded = array('foodID');
	public $primaryKey = 'foodID';
	public $timestamps = false;

}