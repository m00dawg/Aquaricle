<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyWatertestlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE WaterTestLogs 
			CHANGE COLUMN temperature temperature DECIMAL(4,1) unsigned DEFAULT NULL');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE WaterTestLogs 
			CHANGE COLUMN temperature temperature TINYINT unsigned DEFAULT NULL');
	}

}
