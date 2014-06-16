<?php

class EquipmentType extends BaseModel {

	protected $table = 'EquipmentTypes';
	protected $guarded = array('equipmentTypeID');
	public $primaryKey = 'equipmentTypeID';
	public $timestamps = false;
}