<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeColorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Colors', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->tinyInteger('colorID')->unsigned()->autoIncrement();
			$table->integer('color')->unsigned();
			$table->string('name', 24)->nullable()->unique();
		});
		DB::beginTransaction();
		/*
		DB::statement("INSERT INTO Colors (color, name) VALUES (CONV('7FFF00', 16, 10), 'Chartreuse')");
		DB::statement("INSERT INTO Colors (color, name) VALUES (CONV('0000FF', 16, 10), 'Blue')");
		DB::statement("INSERT INTO Colors (color, name) VALUES (CONV('FF8C00', 16, 10), 'DarkOrange')");
		DB::statement("INSERT INTO Colors (color, name) VALUES (CONV('8B0000', 16, 10), 'DarkRed')");
		DB::statement("INSERT INTO Colors (color, name) VALUES (CONV('4B0082', 16, 10), 'Indigo')");
		DB::statement("INSERT INTO Colors (color, name) VALUES (CONV('5F9EA0', 16, 10), 'CadetBlue')");
		DB::statement("INSERT INTO Colors (color, name) 
			VALUES (CONV('C71585', 16, 10), 'MediumVioletRed')");
*/
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('666699', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('660000', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('003300', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('003399', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('000066', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('330066', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('990000', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('006600', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('0033FF', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('660099', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('FFCC00', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('0066FF', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('CC0099', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('FF0000', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('FFFF00', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('00CC00', 16, 10))");
		DB::statement("INSERT INTO Colors (color) VALUES (CONV('0099FF', 16, 10))");
		

		DB::commit();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Colors');
	}

}
