<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('News', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('newsID')->unsigned();
			$table->timestamp('createdAt')
				->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updatedAt')
				->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->string('title', 32);
			$table->text('content');
			$table->primary(array('newsID'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('News');
	}

}
