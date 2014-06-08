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
		DB::beginTransaction();
		$this->call('UsersTableSeeder');
		$this->call('FoodtableSeeder');
		DB::commit();
	}
}

class UsersTableSeeder extends Seeder
{
	public function run()
	{
		$username = 'Admin';
		$password = Hash::make('password');
		User::create(array('username' => $username, 
			'password' =>  Hash::make('secret'),
			'email' => 'email@example.com'));
		
	}
}

class FoodTableSeeder extends Seeder
{
	
	public function run()
	{
		Food::create(array('name' => 'Colored Flakes'));
		Food::create(array('name' => 'Brine Shrimp'));
		Food::create(array('name' => 'Bloodworms'));
		Food::create(array('name' => 'Algae Wafers'));
	}
}