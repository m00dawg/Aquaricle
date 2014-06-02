<?php

class Equipment extends BaseModel {

	protected $table = 'Equipment';
	protected $guarded = array('equipmentID');
	public $primaryKey = 'equipmentID';
	public $timestamps = false;

}