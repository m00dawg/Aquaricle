<?php

//use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Equipment extends BaseModel
{
//    use SoftDeletingTrait;
	
	protected $table = 'Equipment';
	protected $guarded = array('equipmentID');
	protected $dates = ['createdAt', 'updatedAt', 'deletedAt'];
	public $primaryKey = 'equipmentID';
	public $timestamps = true;

	
	public function equimpentType()
	{
		return $this->belongsTo('EquipmentType', 'equipmentTypeID');
	}
}