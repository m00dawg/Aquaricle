<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Equipment', function(Blueprint $table)
		{
			$table->increments('equipmentID')->unsigned();
			$table->integer('aquariumID')->unsigned();
			$table->string('name', 48);
			$table->smallinteger('maintenanceInterval')->unsigned()->nullable();
			$table->timestamp('createdAt')
				->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updatedAt')
				->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->timestamp('deletedAt')->nullable();
			$table->text('comments')->nullable();
			$table->unique(array('aquariumID', 'name'));
			$table->foreign('aquariumID')->references('aquariumID')->on('Aquariums')
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
		Schema::drop('Equipment');
	}

}
