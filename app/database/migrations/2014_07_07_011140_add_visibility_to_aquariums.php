<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVisibilityToAquariums extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Aquariums', function(Blueprint $table)
		{
			$table->enum('visibility', array('Public', 'Private'))
				->after('measurementUnits')
				->default('Public');
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
			$table->dropColumn('visibility');
		});
	}

}
