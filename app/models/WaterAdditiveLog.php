<?php

class WaterAdditiveLog extends BaseModel {

	protected $table = 'WaterAdditiveLogs';
	protected $guarded = array('aquariumLogID');
	public $primaryKey = array('aquariumLogID', 'additiveID');
	public $incrementing = false;
	public $timestamps = false;
	
	public function aquariumLog()
	{
		return $this->belongsTo('AquariumLog');
	}
}
