<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAquariumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Aquariums', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('aquariumID')->unsigned();
			$table->integer('userID')->unsigned();
//			$table->timestamps();
			$table->timestamp('createdAt')
				->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updatedAt')
				->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->timestamp('deletedAt')->nullable();
			$table->enum('measurementUnits', array('Metric', 'Imperial'))->default('Imperial');
			$table->decimal('capacity', 5, 2)->unsigned();
			$table->decimal('length', 5, 2)->unsigned();
			$table->decimal('width', 5, 2)->unsigned();
			$table->decimal('height', 5, 2)->unsigned();
			$table->string('name', 48);
			$table->string('location', 48)->nullable();
			$table->string('aquariduinoHostname', 255)->nullable();
			$table->unique(array('userID', 'name'));
			$table->foreign('userID')->references('userID')->on('Users')
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
		Schema::drop('Aquariums');
	}

}
