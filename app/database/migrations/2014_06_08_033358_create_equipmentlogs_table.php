<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('EquipmentLogs', function(Blueprint $table)
		{
			$table->integer('aquariumLogID')->unsigned();
			$table->integer('equipmentID')->unsigned();
			$table->enum('Maintenance', array('Yes', 'No'));
			$table->primary(array('aquariumLogID', 'equipmentID'));
			
			$table->index('equipmentID');
			$table->foreign('aquariumLogID')->references('aquariumLogID')->on('AquariumLogs')
				->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('equipmentID')->references('equipmentID')->on('Equipment')
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
		Schema::drop('EquipmentLogs');
	}

}
