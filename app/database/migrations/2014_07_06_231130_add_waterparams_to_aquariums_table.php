<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWaterparamsToAquariumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Aquariums', function(Blueprint $table)
		{
			$table->decimal('targetTemperature',4,1)->unsigned()->after('waterChangeInterval')->default(25);
			$table->decimal('targetPH',3,1)->unsigned()->after('targetTemperature')->default(7.0);
			$table->tinyInteger('targetKH')->unsigned()->after('targetPH')->default(10);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Aquariums', function(Blueprint $table)
		{
			$table->dropColumn('targetTemperature');
			$table->dropColumn('targetPH');
			$table->dropColumn('targetKH');
		});
	}

}
