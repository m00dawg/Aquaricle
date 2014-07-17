<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchasepriceUrlToEquip extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Equipment', function(Blueprint $table)
		{
			$table->decimal('purchasePrice', 6, 2)->nullable()->after('name');
			$table->string('url', 255)->nullable()->after('purchasePrice');
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
			$table->dropColumn('purchasePrice');
			$table->dropColumn('url');			
		});
	}

}
