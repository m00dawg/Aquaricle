<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLifeTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('LifeTypes', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->tinyInteger('lifeTypeID')->unsigned()->autoIncrement();
			$table->string('lifeTypeName', 32);
			
			$table->unique('lifeTypeName');
		});
		
		DB::table('LifeTypes')->insert(array('lifeTypeName' => 'Fish'));
		DB::table('LifeTypes')->insert(array('lifeTypeName' => 'Plants'));
		DB::table('LifeTypes')->insert(array('lifeTypeName' => 'Coral'));
		DB::table('LifeTypes')->insert(array('lifeTypeName' => 'Gastropod'));
		DB::table('LifeTypes')->insert(array('lifeTypeName' => 'Crustaceans'));
		DB::commit();
		
		Schema::create('Life', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('lifeID')->unsigned();
			$table->tinyInteger('lifeTypeID')->unsigned()->nullable();
			$table->integer('userID')->unsigned()->nullable();
			$table->string('commonName', 32);
			$table->string('scientificName', 64)->nullabe();
			
			$table->foreign('lifeTypeID')->references('lifeTypeID')->on('LifeTypes')
				->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('userID')->references('userID')->on('Users')
				->onDelete('cascade')->onUpdate('cascade');
		});

		Schema::create('AquariumLife', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('aquariumLifeID')->unsigned();
			$table->integer('lifeID')->unsigned();
			$table->integer('aquariumID')->unsigned();
			$table->timestamp('createdAt')
				->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updatedAt')
				->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->timestamp('deletedAt')->nullable();
			$table->string('nickname', 64);
			$table->tinyInteger('qty')->unsigned();
			$table->decimal('price', 6,2)->nullable();

			$table->foreign('lifeID')->references('lifeID')->on('Life')
				->onDelete('restrict')->onUpdate('cascade');
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
		Schema::drop('AquariumLife');
		Schema::drop('Life');
		Schema::drop('LifeTypes');
	}

}
