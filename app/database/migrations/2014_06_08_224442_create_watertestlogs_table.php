<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWatertestlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('WaterTestLogs', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('aquariumLogID')->unsigned();
			$table->tinyInteger('temperature')->unsigned()->nullable();
			$table->decimal('ammonia', 5, 2)->unsigned()->nullable();
			$table->decimal('nitrites', 5, 2)->unsigned()->nullable();
			$table->decimal('nitrates', 5, 2)->unsigned()->nullable();
			$table->decimal('phosphates', 5, 2)->unsigned()->nullable();
			$table->decimal('pH', 3, 1)->unsigned()->nullable();
			$table->tinyInteger('KH')->unsigned()->nullable();
			$table->smallInteger('amountExchanged')->unsigned()->nullable();
			$table->foreign('aquariumLogID')->references('aquariumLogID')->on('AquariumLogs')
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
		Schema::drop('WaterTestLogs');
	}

}
