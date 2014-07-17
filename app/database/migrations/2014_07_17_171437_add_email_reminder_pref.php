<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailReminderPref extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Users', function(Blueprint $table)
		{
			$table->enum('emailReminders', array('Yes', 'No'))
				->default('Yes')->after('timezoneID');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Users', function(Blueprint $table)
		{
			$table->dropColumn('emailReminders');
		});
	}

}
