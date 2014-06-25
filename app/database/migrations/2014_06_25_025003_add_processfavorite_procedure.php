<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcessfavoriteProcedure extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = "SET SQL_MODE='TRADITIONAL';
		DROP PROCEDURE IF EXISTS ProcessFavoriteLog;

		CREATE PROCEDURE ProcessFavoriteLog (IN inAquariumLogID INT UNSIGNED, IN inAquariumID INT UNSIGNED)
		BEGIN

		DECLARE newAquariumLogID INT UNSIGNED;

		SET AUTOCOMMIT=0;

		INSERT INTO AquariumLogs (aquariumID, comments)
		SELECT AquariumLogFavorites.aquariumID, comments
		FROM AquariumLogFavorites
		JOIN AquariumLogs ON AquariumLogs.aquariumLogID = AquariumLogFavorites.aquariumLogID
		WHERE AquariumLogFavorites.aquariumLogID = inAquariumLogID
		AND AquariumLogFavorites.aquariumID = inAquariumID;

		SELECT LAST_INSERT_ID() INTO newAquariumLogID;

		INSERT INTO FoodLogs 
		SELECT newAquariumLogID, foodID
		FROM AquariumLogFavorites
		JOIN AquariumLogs ON AquariumLogs.aquariumLogID = AquariumLogFavorites.aquariumLogID
		JOIN FoodLogs ON FoodLogs.aquariumLogID = AquariumLogs.aquariumLogID
		WHERE AquariumLogFavorites.aquariumLogID = inAquariumLogID
		AND AquariumLogFavorites.aquariumID = inAquariumID;

		INSERT INTO WaterAdditiveLogs 
		SELECT newAquariumLogID, waterAdditiveID, amount
		FROM AquariumLogFavorites
		JOIN AquariumLogs ON AquariumLogs.aquariumLogID = AquariumLogFavorites.aquariumLogID
		JOIN WaterAdditiveLogs ON WaterAdditiveLogs.aquariumLogID = AquariumLogs.aquariumLogID
		WHERE AquariumLogFavorites.aquariumLogID = inAquariumLogID
		AND AquariumLogFavorites.aquariumID = inAquariumID;

		INSERT INTO EquipmentLogs 
		SELECT newAquariumLogID, equipmentID, maintenance
		FROM AquariumLogFavorites
		JOIN AquariumLogs ON AquariumLogs.aquariumLogID = AquariumLogFavorites.aquariumLogID
		JOIN EquipmentLogs ON EquipmentLogs.aquariumLogID = AquariumLogs.aquariumLogID
		WHERE AquariumLogFavorites.aquariumLogID = inAquariumLogID
		AND AquariumLogFavorites.aquariumID = inAquariumID;

		COMMIT;

		END ;";
		
		DB::unprepared($sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::unprepared("DROP PROCEDURE IF EXISTS ProcessFavoriteLog");
	}


}
