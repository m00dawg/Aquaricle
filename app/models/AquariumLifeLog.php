<?php

class AquariumLifeLog extends BaseModel {

	protected $table = 'AquariumLifeLogs';
	protected $guarded = array('aquariumLogID', 'lifeID');
	public $primaryKey = array('aquariumLogID', 'lifeID');
	public $incrementing = false;
	public $timestamps = false;

	/* Relationships */
	public function aquariumLog()
	{
		return $this->belongsTo('AquariumLog', 'aquariumLogID');
	}

	public function life()
	{
		return $this->belongsTo('Life', 'lifeID');
	}

}


