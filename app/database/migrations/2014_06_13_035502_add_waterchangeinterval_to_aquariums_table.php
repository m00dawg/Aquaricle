<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWaterchangeintervalToAquariumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Aquariums', function(Blueprint $table)
		{
			$table->tinyInteger('waterChangeInterval')->unsigned()->after('height')->default(14);
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
			$table->dropColumn('waterChangeInterval');
		});
	}

}
