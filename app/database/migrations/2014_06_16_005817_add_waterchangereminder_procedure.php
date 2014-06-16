<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWaterchangereminderProcedure extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = "DROP PROCEDURE IF EXISTS WaterChangesDue;

			CREATE PROCEDURE WaterChangesDue (IN inUserID INT UNSIGNED, IN inDueOffset TINYINT UNSIGNED)
			BEGIN

			SELECT Aquariums.aquariumID, Aquariums.name, 
				AquariumLogs.logDate, WaterTestLogs.amountExchanged, Aquariums.measurementUnits
			FROM 
				(SELECT Aquariums.aquariumID, MAX(AquariumLogs.logDate) AS logDate
				FROM Aquariums
				JOIN AquariumLogs ON AquariumLogs.aquariumID = Aquariums.aquariumID
				JOIN WaterTestLogs ON WaterTestLogs.aquariumLogID = AquariumLogs.aquariumLogID
				AND amountExchanged IS NOT NULL
				AND userID = inUserID
				GROUP BY Aquariums.aquariumID) AS LastWaterChanges
			JOIN AquariumLogs ON AquariumLogs.aquariumID = LastWaterChanges.aquariumID
				AND AquariumLogs.logDate = LastWaterChanges.logDate
			JOIN Aquariums ON Aquariums.aquariumID = AquariumLogs.aquariumID
			JOIN WaterTestLogs ON WaterTestLogs.aquariumLogID = AquariumLogs.aquariumLogID
			WHERE DATEDIFF(NOW(), AquariumLogs.logDate) > Aquariums.waterChangeInterval - inDueOffset;
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
		DB::unprepared("DROP PROCEDURE IF EXISTS WaterChangesDue");
	}

}
