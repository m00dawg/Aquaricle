<?php

class FoodLog extends BaseModel {

	protected $table = 'FoodLogs';
	protected $guarded = array('aquariumLogID');
	public $primaryKey = 'aquariumLogID';
	public $incrementing = false;
	public $timestamps = false;
}
