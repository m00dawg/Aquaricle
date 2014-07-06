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

	public function nextWaterChangeClass()
	{
		if(!isset($this->daysRemaining))
			return '';
		if($this->daysRemaining > 2)
			return '';
		if($this->daysRemaining > 1)
			return 'waterChangeDueSoon';
		if($this->daysRemaining > 0)
			return 'waterChangeDue';
		return 'waterChangePastDue';
	}

	/* Currently these are freshwater colors - should add salt */
	public function ammoniaBackgroundColor()
	{
		if(!isset($this->ammonia))
			return 'clear';
		if($this->ammonia >= 8.00)
			return '#17754a';
		if($this->ammonia >= 4.00)
			return '#2ac93a';
		if($this->ammonia >= 2.00)
			return '#50dc22';
		if($this->ammonia >= 1.00)
			return '#83fb03';
		if($this->ammonia >= 0.50)
			return '#e0ff0d';
		if($this->ammonia >= 0.25)
			return '#f5ff08';
		return '#fcfe31';	
	}
	
	public function nitriteBackgroundColor()
	{
		if(!isset($this->nitrites))
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
		if(!isset($this->nitrates))
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

	public function phosphateBackgroundColor()
	{
		if(!isset($this->phosphates))
			return 'clear';
		if($this->phosphates >= 10.00)
			return '#183c52';
		if($this->phosphates >= 5.00)
			return '#40595e';
		if($this->phosphates >= 2.00)
			return '#a4e6bb';
		if($this->phosphates >= 1.00)
			return '#c8f6a9';
		if($this->phosphates >= 0.50)
			return '#ddeb94';
		if($this->phosphates >= 0.25)
			return '#f7fca7';
		return '#f7f9b0';
	}

}
