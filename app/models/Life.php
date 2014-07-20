<?php

class Life extends BaseModel {

	protected $table = 'Life';
	protected $guarded = array('lifeID', 'userID');
	public $primaryKey = 'userID';
	public $timestamps = false;

}