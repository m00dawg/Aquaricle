<?php

class LifeType extends BaseModel {

	protected $table = 'LifeTypes';
	protected $guarded = array('lifeTypeID');
	public $primaryKey = 'lifeTypeID';
	public $timestamps = false;

}