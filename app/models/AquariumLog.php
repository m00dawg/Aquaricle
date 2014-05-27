<?php

class AquariumLog extends BaseModel {

	protected $table = 'AquariumLogs';
	protected $guarded = array('aquariumLogID', 'aquariumID');
	public $timestamps = false;
	public $primaryKey = 'aquariumLogID';
	
	public function user()
	{
		return $this->belongsTo('Aquarium');
	}

	public function waterTestLogs()
	{
		return $this->hasOne('WaterTestLog', 'aquariumLogID');
	}
}
