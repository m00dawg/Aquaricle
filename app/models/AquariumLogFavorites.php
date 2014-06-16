<?php

class AquariumLogFavorites extends BaseModel {

	protected $table = 'AquariumLogFavorites';
	protected $guarded = array('aquariumLogID');
	public $primaryKey = 'aquariumLogID';
	public $incrementing = false;
	public $timestamps = false;
}
