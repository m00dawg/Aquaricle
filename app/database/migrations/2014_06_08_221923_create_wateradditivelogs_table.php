<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWateradditivelogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('WaterAdditiveLogs', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('aquariumLogID')->unsigned();
			$table->tinyInteger('waterAdditiveID')->unsigned();
			$table->decimal('amount', 5, 2)->unsigned();
			$table->primary(array('aquariumLogID', 'waterAdditiveID'));
			$table->index('waterAdditiveID');
			$table->foreign('aquariumLogID')->references('aquariumLogID')->on('AquariumLogs')
				->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('waterAdditiveID')->references('waterAdditiveID')->on('WaterAdditives')
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
		Schema::drop('WaterAdditiveLogs');
	}

}
