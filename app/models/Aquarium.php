<?php

class Aquarium extends BaseModel {

	protected $table = 'Aquariums';
	protected $guarded = array('aquariumID', 'userID', 'visibility', 'updatedAt', 'deletedAt');
	public $primaryKey = 'aquariumID';
	public $timestamps = true;
	
	public function getMeasurementUnits()
	{
		if($this->measurementUnits == 'Metric')
			return array('Volume' => 'L', 'Length' => 'cm', 'Temperature' => 'C');
		else
			return array('Volume' => 'Gal', 'Length' => 'in', 'Temperature' => 'F');		
	}
	
	/* Query Scopes */
	public function scopeByAuthUser($query)
	{
		return $query->where('userID', '=', Auth::user()->userID);
	}
	
	public function scopeSingleAquarium($query, $aquariumID)
	{
		$aquarium = self::where('aquariumID', '=', $aquariumID)
			->first();
		if($aquarium)
		{
			if($aquarium->visibility != 'Public' && $aquarium->userID == Auth::user()->userID)
				return $aquarium;
			if($aquarium->visibility == 'Public')
				return $aquarium;
		}		
	}
	
	/* Relationships */
	public function user()
	{
		return $this->belongsTo('User', 'userID');
	}
	
	public function aquariumProfile()
	{
		return $this->belongsTo('AquariumProfiles', 'aquariumProfileID');
	}

	public function aquariumLogs()
	{
		return $this->hasMany('AquariumLog', 'aquariumID');
	}
	
	public function equipment()
	{
		return $this->hasMany('Equipment', 'aquariumID');
	}


}
