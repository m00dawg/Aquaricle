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

	public function sparkTemperature()
	{
	    $key = 'Aquarium:sparkTemperature';
		if(Cache::has($key))
	        return Cache::get($key);
	   
		 Config::get('spark.url');
		 $curl = curl_init(Config::get('spark.url').
			$this->sparkID."/temperature?access_token=".
			$this->sparkToken);
		 curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		 $curlResponse = curl_exec($curl);
		 if ($curlResponse === false)
		 {
		     $info = curl_getinfo($curl);
		     curl_close($curl);
			 return null;
		 }
		 curl_close($curl);
		 $decoded = json_decode($curlResponse);
		 if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			 return null;
		 }
		 Cache::put($key, $decoded->result, Config::get('cache.ttl'));
		 return $decoded->result;
	}
}
