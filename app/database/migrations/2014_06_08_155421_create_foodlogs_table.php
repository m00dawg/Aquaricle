<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('FoodLogs', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('aquariumLogID')->unsigned();
			$table->tinyInteger('foodID')->unsigned();
			$table->primary(array('aquariumLogID', 'foodID'));
			$table->index('foodID');
			$table->foreign('aquariumLogID')->references('aquariumLogID')->on('AquariumLogs')
				->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('foodID')->references('foodID')->on('Food')
				->onDelete('cascade')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('FoodLogs');
	}

}
