<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmenttypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('EquipmentTypes', function(Blueprint $table)
		{	
			$table->engine = 'InnoDB';
			$table->tinyInteger('equipmentTypeID')->unsigned()->autoIncrement();
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
		Schema::drop('EquipmentTypes');
	}

}
