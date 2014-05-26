<?php

class AquariumLog extends BaseModel {

	protected $table = 'AquariumLogs';
	protected $guarded = array('aquariumLogID', 'aquariumID');
	public $primaryKey = 'aquariumLogID';
	

	public function user()
	{
		return $this->belongsTo('Aquarium');
	}

}
