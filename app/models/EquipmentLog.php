<?php

class EquipmentLog extends BaseModel {

	protected $table = 'EquipmentLogs';
	protected $guarded = array('aquariumLogID');
	//public $primaryKey = array('aquariumLogID', 'additiveID');
	public $primaryKey = 'aquariumLogID';
	public $incrementing = false;
	public $timestamps = false;
}
