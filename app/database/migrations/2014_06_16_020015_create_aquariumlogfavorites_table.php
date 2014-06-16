<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAquariumlogfavoritesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('AquariumLogFavorites', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('aquariumLogID')->unsigned();
			$table->integer('aquariumID')->unsigned();
			$table->string('name', 48);
			$table->primary(array('aquariumLogID'));
			$table->foreign('aquariumID')->references('aquariumID')->on('Aquariums')
					->onDelete('cascade')->onUpdate('cascade');
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
		Schema::drop('AquariumLogFavorites');
	}

}
