<?php

class Aquarium extends BaseModel {

	protected $table = 'Aquariums';
	protected $guarded = array('aquariumID', 'userID', 'updatedAt', 'deletedAt');
	public $primaryKey = 'aquariumID';

	public function user()
	{
		return $this->belongsTo('User');
	}

}
