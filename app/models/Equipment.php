<?php
// Laravel 4.2 Only
//use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Equipment extends BaseModel
{

// Laravel 4.2 Only
//    use SoftDeletingTrait;
	protected $table = 'Equipment';
	protected $guarded = array('equipmentID');
	protected $dates = ['createdAt', 'updatedAt', 'deletedAt'];
	public $primaryKey = 'equipmentID';
	public $timestamps = true;


}