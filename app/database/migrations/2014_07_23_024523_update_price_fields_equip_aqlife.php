<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePriceFieldsEquipAqlife extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE Equipment
			MODIFY COLUMN purchasePrice decimal(6,2) unsigned DEFAULT NULL');		
		DB::statement('ALTER TABLE AquariumLife
			CHANGE COLUMN price purchasePrice decimal(6,2) unsigned DEFAULT NULL,
			MODIFY COLUMN lifeID int unsigned NOT NULL AFTER aquariumID,
			MODIFY COLUMN comments text DEFAULT NULL,
			MODIFY COLUMN purchasedAt varchar(64) DEFAULT NULL');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE Equipment
			MODIFY COLUMN purchasePrice decimal(6,2) DEFAULT NULL');
		DB::statement('ALTER TABLE AquariumLife
			CHANGE COLUMN purchasePrice price decimal(6,2) DEFAULT NULL,
			MODIFY COLUMN lifeID int unsigned NOT NULL AFTER aquariumLifeID,
			MODIFY COLUMN comments text,
			MODIFY COLUMN purchasedAt varchar(32) DEFAULT NULL');
	}
}
