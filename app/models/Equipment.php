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
	
	public function nextMaintClass()
	{
		if(!isset($this->nextMaintDays))
			return "";
		if($this->nextMaintDays > 2)
			return "";
		if($this->nextMaintDays > 1)
			return "equipmentMaintDueSoon";
		if($this->nextMaintDays > 0)
			return "equipmentMaintDue";
		return "equipmentMaintOverdue";
	}
}