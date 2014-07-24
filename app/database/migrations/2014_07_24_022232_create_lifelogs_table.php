<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLifelogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('LifeLogs', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('aquariumLogID')->unsigned();
			$table->integer('lifeID')->unsigned();

			$table->primary(array('aquariumLogID', 'lifeID'));
			$table->foreign('aquariumLogID')->references('aquariumLogID')->on('AquariumLogs')
				->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('lifeID')->references('lifeID')->on('Life')
				->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('LifeLogs');
	}

}
