<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyLifeSciname extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE Life
			MODIFY COLUMN scientificName varchar(64) DEFAULT NULL,
			MODIFY COLUMN description text DEFAULT NULL');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE Life
			MODIFY COLUMN scientificName varchar(64) NOT NULL,
			MODIFY COLUMN description text NOT NULL');
	}

}
