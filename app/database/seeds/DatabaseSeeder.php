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
		$this->call('WaterAdditivesTableSeeder');
		$this->call('EquipmentTypesTableSeeder');
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

class WaterAdditivesTableSeeder extends Seeder
{
	public function run()
	{
		WaterAdditive::create(array('name' => 'Seachem Prime'));
		WaterAdditive::create(array('name' => 'Seachem Stabilty'));
		WaterAdditive::create(array('name' => 'Seachem Flourish Excel'));
		WaterAdditive::create(array('name' => 'Seachem Flourish Comprehensive'));
		WaterAdditive::create(array('name' => 'Nualgi'));
	}
}

class EquipmentTypesTableSeeder extends Seeder
{
	public function run()
	{
		EquipmentTypes::create(array('name' => 'Other'));
		EquipmentTypes::create(array('name' => 'Filtration'));
		EquipmentTypes::create(array('name' => 'Lighting'));
		EquipmentTypes::create(array('name' => 'Climate Control'));
		EquipmentTypes::create(array('name' => 'Planting'));
		EquipmentTypes::create(array('name' => 'Aeration'));
		EquipmentTypes::create(array('name' => 'Automation'));
	}
}