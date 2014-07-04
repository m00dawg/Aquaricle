<?php

class WaterTestLog extends BaseModel {

	protected $table = 'WaterTestLogs';
	protected $guarded = array('aquariumLogID');
	public $primaryKey = 'aquariumLogID';
	public $timestamps = false;

	public function aquariumLog()
	{
		return $this->belongsTo('AquariumLog');
	}

	public function nitriteBackgroundColor()
	{
		$nitrites = $this->nitrites;
		if(!isset($nitrites))
			return 'clear';
		if($this->nitrites >= 5.00)
			return '#b958ab';
		if($this->nitrites >= 2.00)
			return '#e467e4';
		if($this->nitrites >= 1.00)
			return '#ca85ef';
		if($this->nitrites >= 0.50)
			return '#c0aaf3';
		if($this->nitrites >= 0.25)
			return '#c9acef';
		return '#95dded';	
	}

	public function nitrateBackgroundColor()
	{
		$nitrates = $this->nitrates;
		if(!isset($nitrates))
			return 'clear';
		if($this->nitrates >= 160)
			return '#9c0d2a';
		if($this->nitrates >= 80)
			return '#ef455d';
		if($this->nitrates >= 40)
			return '#ee2f3a';
		if($this->nitrates >= 20)
			return '#ef3f31';
		if($this->nitrates >= 10)
			return '#f58a2b';
		if($this->nitrates >= 5)
			return '#faf021';
		return '#fbff0d';
	}

}
