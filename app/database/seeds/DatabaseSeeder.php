<?php

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		DB::startTransaction();
		$this->call('FoodtableSeeder');
		DB::commit();
	}
}

class UsersTableSeeder extends Seeder
{
	public funciton run()
	{
		$username = 'Admin'
		$password = Hash::make('password');
		Users::create(array('username' => $username, ))
		
	}
}

class FoodTableSeeder extends Seeder
{
	
	publiuc function run()
	{
		Food::create(array('name' => 'Colored Flakes'));
		Food::create(array('name' => 'Brine Shrimp'));
		Food::create(array('name' => 'Bloodworms'));
		Food::create(array('name' => 'Algae Wafers'));
	}
}