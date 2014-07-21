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
			$table->tinyInteger('lifeTypeID')->unsigned();
			$table->string('lifeTypeName', 32);
			
			$table->primary('lifeTypeID');
			$table->unique('lifeTypeName');
		});
		
		DB::table('LifeTypes')->insert(array('lifeTypeID' => 1, 'lifeTypeName' => 'Fish'));
		DB::table('LifeTypes')->insert(array('lifeTypeID' => 2, 'lifeTypeName' => 'Plants'));
		DB::table('LifeTypes')->insert(array('lifeTypeID' => 3, 'lifeTypeName' => 'Coral'));
		DB::table('LifeTypes')->insert(array('lifeTypeID' => 4, 'lifeTypeName' => 'Gastropod'));
		DB::table('LifeTypes')->insert(array('lifeTypeID' => 5, 'lifeTypeName' => 'Crustaceans'));
		DB::table('LifeTypes')->insert(array('lifeTypeID' => 255, 'lifeTypeName' => 'Other'));
		DB::commit();
		
		Schema::create('Life', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('lifeID')->unsigned();
			$table->tinyInteger('lifeTypeID')->unsigned();
			$table->integer('userID')->unsigned()->nullable();
			$table->string('commonName', 32);
			$table->string('scientificName', 64)->nullabe();
			$table->text('description')->nullbable();
			
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
			$table->string('nickname', 48)->nullable();
			$table->tinyInteger('qty')->unsigned();
			$table->decimal('price', 6,2)->nullable();
			$table->string('purchasedAt', 32)->nullable();
			$table->text('comments')->nullable();

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
