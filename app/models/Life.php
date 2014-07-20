<?php

class Life extends BaseModel {

	protected $table = 'Life';
	protected $guarded = array('lifeID', 'userID');
	public $primaryKey = 'userID';
	public $timestamps = false;

	/* Relationships */
	public function user()
	{
		return $this->belongsTo('User', 'userID');
	}

	public function lifeType()
	{
		return $this->belongsTo('LifeTypes', 'lifeTypeID');
	}

}