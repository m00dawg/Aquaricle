<?php

class AquariumLife extends BaseModel {

	protected $table = 'AquariumLife';
	protected $guarded = array('aquariumLifeID', 'lifeID', 'userID');
	public $primaryKey = 'aquariumLifeID';
	protected $dates = ['createdAt', 'updatedAt', 'deletedAt'];
	public $timestamps = true;

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