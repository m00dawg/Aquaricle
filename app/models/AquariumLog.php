<?php

class AquariumLog extends BaseModel {

	protected $table = 'AquariumLogs';
	protected $guarded = array('aquariumLogID', 'aquariumID');
	public $primaryKey = 'aquariumLogID';
	public $timestamps = false;
	
	public function user()
	{
		return $this->belongsTo('Aquarium');
	}

	public function waterTestLog()
	{
		return $this->hasOne('WaterTestLog', 'aquariumLogID');
	}
}
