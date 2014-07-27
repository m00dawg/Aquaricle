<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSparkioToAquariums extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE Aquariums
			DROP COLUMN aquariduinoHostname,
			ADD COLUMN sparkID char(24) DEFAULT NULL AFTER location,
			ADD COLUMN sparkToken char(40) DEFAULT NULL AFTER sparkID');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE Aquariums
			DROP COLUMN sparkID,
			DROP COLUMN sparkToken,
			ADD COLUMN aquariduinoHostname varchar(255) DEFAULT NULL');
	}

}
