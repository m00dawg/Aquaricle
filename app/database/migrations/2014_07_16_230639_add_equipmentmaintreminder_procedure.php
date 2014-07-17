<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEquipmentmaintreminderProcedure extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = "SET SQL_MODE='TRADITIONAL';
			DROP PROCEDURE IF EXISTS EquipmentMaintenanceDue;

			CREATE PROCEDURE EquipmentMaintenanceDue
				(IN inUserID INT UNSIGNED, IN inDueOffset TINYINT UNSIGNED)
			BEGIN

			SELECT Equipment.aquariumID, Equipment.name, 
				CAST(maintInterval AS signed) - DATEDIFF(NOW(), AquariumLogs.logDate) AS dueIn,
				AquariumLogs.logDate
			FROM 
				(SELECT Aquariums.aquariumID, EquipmentLogs.equipmentID, 
					MAX(AquariumLogs.logDate) AS logDate
					FROM Aquariums
					JOIN AquariumLogs ON AquariumLogs.aquariumID = Aquariums.aquariumID
					JOIN EquipmentLogs ON EquipmentLogs.aquariumLogID = AquariumLogs.aquariumLogID
						AND maintenance = 'Yes'
						AND userID = inUserID
					GROUP BY EquipmentLogs.equipmentID) AS LastEquipmentChanges
			JOIN AquariumLogs ON AquariumLogs.aquariumID = LastEquipmentChanges.aquariumID
			AND AquariumLogs.logDate = LastEquipmentChanges.logDate
			JOIN Equipment ON Equipment.equipmentID = LastEquipmentChanges.equipmentID
			WHERE DATEDIFF(NOW(), AquariumLogs.logDate) > CAST(maintInterval AS signed) - inDueOffset;

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
		DB::unprepared("DROP PROCEDURE IF EXISTS EquipmentMaintenanceDue");
	}

}
