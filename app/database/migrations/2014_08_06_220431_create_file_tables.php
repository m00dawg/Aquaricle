<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Files', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('fileID')->unsigned()->autoIncrement();
			$table->integer('aquariumID')->unsigned();
			$table->timestamp('createdAt')
				->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updatedAt')
				->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->enum('fileType', array('jpg', 'png'));
			$table->string('title', 48)->nullable();
			$table->text('caption')->nullable();
			$table->foreign('aquariumID')->references('aquariumID')->on('Aquariums')
				->onDelete('cascade')->onUpdate('cascade');
		});
		
		Schema::create('AquariumLogFiles', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('fileID')->unsigned();
			$table->integer('aquariumLogID')->unsigned();
			$table->primary(array('fileID', 'aquariumLogID'));
			$table->foreign('fileID')->references('fileID')->on('Files')
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
		Schema::drop('AquariumLogFiles');
		Schema::drop('AquariumFiles');
	}

}
