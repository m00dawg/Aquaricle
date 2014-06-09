<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWateradditivesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('WaterAdditives', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->tinyInteger('waterAdditiveID')->unsigned()->autoIncrement();
			$table->string('name', 48);
			$table->text('description')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('WaterAdditives');
	}

}
