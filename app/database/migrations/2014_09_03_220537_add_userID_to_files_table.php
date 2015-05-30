<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIDToFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE Files
			ADD COLUMN userID int unsigned DEFAULT NULL AFTER fileID,
			ADD INDEX `files_userid_foreign` (`userID`),
			ADD CONSTRAINT `files_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`)
				ON DELETE CASCADE ON UPDATE CASCADE');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE Files
			DROP FOREIGN KEY files_userid_foreign,
			DROP COLUMN userID');
	}

}
