<?php

class AquariumProfile extends BaseModel {

	protected $table = 'AquariumProfiles';
	protected $guarded = array('aquariumLogID', 'aquariumID');
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
