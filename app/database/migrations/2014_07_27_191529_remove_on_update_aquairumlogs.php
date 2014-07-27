<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOnUpdateAquairumlogs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE AquariumLogs
			MODIFY COLUMN logDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE AquariumLogs
			MODIFY COLUMN logDate timestamp NOT NULL 
				DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
	}

}
