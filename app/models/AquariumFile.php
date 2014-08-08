<?php

class AquariumFile extends BaseModel {

	protected $table = 'Files';
	protected $guarded = array('fileID', 'aquariumID');
	public $primaryKey = 'fileID';
	protected $dates = ['createdAt', 'updatedAt'];
	public $timestamps = true;
	
	public static $thumbHeight = 160;
	public static $fullHeight = 2048;


	/* Relationships */
	public function aquarium()
	{
		return $this->belongsTo('Aquarium', 'aquariumID');
	}
}
