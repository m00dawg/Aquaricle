<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Food', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->tinyInteger('foodID')->unsigned()->autoIncrement();
			$table->string('name', 48);

			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Food');
	}

}
