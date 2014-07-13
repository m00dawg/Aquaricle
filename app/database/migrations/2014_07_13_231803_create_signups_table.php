<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Signups', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('username', 64);
			$table->string('password', 128);
			$table->string('email', 255);
			$table->string('token', 32);
			$table->timestamp('createdAt')
				->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updatedAt')
				->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->primary('username');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Signups');
	}

}
