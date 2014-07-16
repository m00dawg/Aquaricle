<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeGhTdsNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE WaterTestLogs 
			MODIFY COLUMN GH tinyint unsigned DEFAULT NULL,
			MODIFY COLUMN TDS tinyint unsigned DEFAULT NULL');
			DB::statement('UPDATE WaterTestLogs SET GH = NULL, TDS = NULL WHERE GH = 0 AND TDS = 0');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE WaterTestLogs 
			MODIFY COLUMN GH tinyint unsigned,
			MODIFY COLUMN TDS tinyint unsigned');
	}

}
