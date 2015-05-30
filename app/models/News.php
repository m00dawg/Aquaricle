<?php

class News extends BaseModel {

	protected $table = 'News';
	protected $guarded = array('newsID');
	public $primaryKey = 'newsID';
	protected $dates = ['createdAt', 'updatedAt'];
	public $timestamps = true;

}
