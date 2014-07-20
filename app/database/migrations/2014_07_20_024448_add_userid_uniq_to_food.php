<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUseridUniqToFood extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Food', function(Blueprint $table)
		{
			$table->integer('userID')->unsigned()->nullable()->after('foodID');
			$table->foreign('userID')->references('userID')->on('Users')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->unique(array('userID', 'name'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Food', function(Blueprint $table)
		{
			$table->dropForeign('food_userid_foreign');
			$table->dropUnique('food_userid_name_unique');
			$table->dropColumn('userID');
		});
	}

}
