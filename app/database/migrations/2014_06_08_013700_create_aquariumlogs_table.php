<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAquariumlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('AquariumLogs', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('aquariumLogID')->unsigned();
			$table->integer('aquariumID')->unsigned();
			$table->timestamp('logDate')
				->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->text('summary');
			$table->text('comments');
			
			$table->index('aquariumID');
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
		Schema::drop('AquariumLogs');
	}

}
