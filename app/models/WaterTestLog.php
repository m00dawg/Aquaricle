<?php

class WaterTestLog extends BaseModel {

	protected $table = 'WaterTestLogs';
	protected $guarded = array('aquariumLogID');
	public $primaryKey = 'aquariumLogID';

	public function aquariumLog()
	{
		return $this->belongsTo('AquariumLog');
	}

}
