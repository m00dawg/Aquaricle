<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGhTdsToWaterlogs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('WaterTestLogs', function(Blueprint $table)
		{
			$table->tinyInteger('GH')->unsigned()->after('KH');
			$table->smallInteger('TDS')->unsigned()->after('gH');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$table->dropColumn('GH');
		$table->dropColumn('TDS');
	}
}
