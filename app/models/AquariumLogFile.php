<?php

class AquariumLogFile extends BaseModel {

	protected $table = 'AquariumLogFiles';
	protected $guarded = array('fileID', 'aquariumLogID');
//	public $primaryKey = 'fileID';
	public $timestamps = false;

	/* Relationships */
	public function file()
	{
		return $this->belongsTo('File', 'fileID');
	}
	
	public function aquariumLog()
	{
		return $this->belongsTo('AquariumLog', 'aquariumLogID');		
	}
}
