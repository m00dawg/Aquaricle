<?php

class AquariumLife extends BaseModel {

	protected $table = 'AquariumLife';
	protected $guarded = array('aquariumLifeID', 'lifeID', 'userID');
	public $primaryKey = 'aquariumLifeID';
	protected $dates = ['createdAt', 'updatedAt', 'deletedAt'];
	public $timestamps = true;

	/* Relationships */
	public function user()
	{
		return $this->belongsTo('User', 'userID');
	}

	public function life()
	{
		return $this->belongsTo('Life', 'lifeID');
	}

	public function scopeFish($query, $aquariumID)
	{
		DB::statement('SELECT @colorsCnt := (SELECT MAX(colorID) FROM Colors)');
		DB::statement('SELECT @rowNumber := 0');
		return $query->where('aquariumID', '=', $aquariumID)
			->join('Life', 'Life.lifeID', '=', 'AquariumLife.lifeID')
			->join('LifeTypes', 'LifeTypes.lifeTypeID',
				'=', 'Life.lifeTypeID')
			->where('LifeTypes.lifeTypeName', '=', 'Fish')
			->whereNull('deletedAt')
			->groupBy('AquariumLife.lifeID')
			->selectRaw("@rowNumber:=@rowNumber + 1 AS rowNumber, 
				Life.commonName AS label, SUM(AquariumLife.qty) AS value,
				(SELECT CONCAT('#', LPAD(CONV(color, 10, 16), 6, '0'))
					FROM Colors WHERE colorID = (@rowNumber % @colorsCnt)) AS color");
	}
}