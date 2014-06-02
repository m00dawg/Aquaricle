<?php

class WaterAdditive extends BaseModel {

	protected $table = 'WaterAdditives';
	protected $guarded = array('waterAdditiveID');
	public $primaryKey = 'waterAdditiveID';
	public $timestamps = false;

}
