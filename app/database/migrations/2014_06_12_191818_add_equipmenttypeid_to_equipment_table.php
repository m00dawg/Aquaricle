<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEquipmenttypeidToEquipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Equipment', function(Blueprint $table)
		{
			$table->tinyInteger('equipmentTypeID')->unsigned()->after('aquariumiD')->default(1);
			$table->foreign('equipmentTypeID')->references('equipmentTypeID')->on('EquipmentTypes')
				->onDelete('restrict')->onUpdate('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Equipment', function(Blueprint $table)
		{
			$table->dropForeign('equipment_equipmenttypeid_foreign');
			$table->dropIndex('equipment_equipmenttypeid_foreign');
			$table->dropColumn('equipmentTypeID');
		});
	}

}
