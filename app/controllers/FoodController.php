<?php

class FoodController extends \BaseController
{
	public function index()
	{
		$globalFood = Food::whereNull('userID')
			->orderBy('name')
			->get();
		
		$userFood = Food::where('userID', '=', Auth::id())
			->orderBy('name')
			->get();		
			
		return View::make('food/index')
			->with('globalFood', $globalFood)
			->with('userFood', $userFood);
	}
	
	public function create()
	{
		return View::make('food/editfood');
	}
	
	public function store()
	{
		$validator = Validator::make(
			Input::all(),
			array('name' => "required|unique:Food,name,NULL,name,userID,".auth::id())
		);
		if ($validator->fails())
		{
			return Redirect::to('food/create')
				->withInput(Input::all())
	 			->withErrors($validator);			
		}
		$food = new Food();
		$food->name = Input::get('name');
		$food->description = Input::get('description');
		$food->userID = auth::id();
		$food->save();		
		return Redirect::to('food')
			->withErrors(array('message' => 'Food Added!'));
	}
	
	public function edit($foodID)
	{
		$food = Food::where('userID', '=', Auth::id())
			->where('foodID', '=', $foodID)
			->first();
		if(count($food) == 1)
		{
			return View::make('food/editfood')
				->with('food', $food);	
		}
		return Redirect::to("food")
			->withErrors(array('message' => 'Food item not found or is un-editable (it may be a global item)'));
	}
	
	public function update($foodID)
	{
		$food = Food::where('userID', '=', Auth::id())
			->where('foodID', '=', $foodID)
			->first();
		if(count($food) == 1)
		{
			if(Input::get('delete'))
			{
				$food->delete();
				return Redirect::to('food')
					->withErrors(array('message' => 'Food Deleted!'));
			}
			
			$validator = Validator::make(
				Input::all(),
				array('name' => "required|unique:Food,name,".$food->name.",name,userID,".auth::id())
			);
			if ($validator->fails())
				return Redirect::to("food/$foodID/edit")
					->withInput(Input::all())
		 			->withErrors($validator);
			$food->name = Input::get('name');
			$food->description = Input::get('description');
			$food->userID = auth::id();
			$food->save();		
			return Redirect::to('food')
				->withErrors(array('message' => 'Food Updated!'));
		}
		return Redirect::to('food')
			->withErrors(array('message' => 'Food item not found or is un-editable (it may be a global item)'));
	}
	
	public function getFeedings($aquariumID)
	{
		$days = Input::get('days');
		if(!isset($days))
			$days = 30;		


		DB::statement('SELECT @colorsCnt := (SELECT MAX(colorID) FROM Colors)');
		DB::statement('SELECT @rowNumber := 0');
		
		$food = DB::select(
			"SELECT @rowNumber:=@rowNumber + 1 AS rowNumber,
				Food.name AS label, COUNT(Food.name) AS value,
 				(SELECT CONCAT('#', LPAD(CONV(color, 10, 16), 6, '0'))
 					FROM Colors WHERE colorID = (@rowNumber % @colorsCnt)) AS color
			 FROM AquariumLogs
			 JOIN FoodLogs ON FoodLogs.aquariumLogID = AquariumLogs.aquariumLogID
			 JOIN Food ON Food.foodID = FoodLogs.foodID
			 WHERE AquariumLogs.aquariumID = ?
			 AND logDate >= DATE_SUB(NOW(), INTERVAL ? Day)
			 GROUP BY Food.name", array($aquariumID, $days));
		$logs = AquariumLog::where('aquariumID', '=', $aquariumID)
			->join('FoodLogs', 'FoodLogs.aquariumLogID', '=', 'AquariumLogs.aquariumLogID')
			->groupby('logDate')
			->orderby('logDate', 'desc')
			->paginate(20);
		return View::make('food/feedings')
			->with('aquariumID', $aquariumID)
			->with('days', $days)
			->with('food', $food)
			->with('foodGraphData', json_encode($food, JSON_NUMERIC_CHECK))
			->with('logs', $logs);
	}


}