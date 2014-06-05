<?php

class AquariumLogsController extends BaseController
{
	
	private function updateWaterTestLog($aquariumLogID)
	{
		if(Input::get('temperature') != '' ||
		   Input::get('ammonia') != '' ||
		   Input::get('nitrites') != '' ||
		   Input::get('nitrates') != '' ||
		   Input::get('phosphates') != '' ||
		   Input::get('pH') != '' ||
		   Input::get('KH') != '' ||
		   Input::get('amountExchanged') != '')
		{
			$waterTestLog = WaterTestLog::FirstOrNew(array('aquariumLogID' => $aquariumLogID));
			$waterTestLog->aquariumLogID = $aquariumLogID;
			if(Input::get('temperature') != '')
				$waterTestLog->temperature = Input::get('temperature');
			if(Input::get('ammonia') != '')
				$waterTestLog->ammonia = Input::get('ammonia');
			if(Input::get('nitrites') != '')
				$waterTestLog->nitrites = Input::get('nitrites');
			if(Input::get('nitrates') != '')
				$waterTestLog->nitrates = Input::get('nitrates');
			if(Input::get('phosphates') != '')
				$waterTestLog->phosphates = Input::get('phosphates');
			if(Input::get('pH') != '')
				$waterTestLog->pH = Input::get('pH');
			if(Input::get('KH') != '')
				$waterTestLog->KH = Input::get('KH');
			if(Input::get('amountExchanged') != '')
				$waterTestLog->amountExchanged = Input::get('amountExchanged');	
			$waterTestLog->save();
			return 'Tested Water';
		}
	}
	
	// We had to use DB::table here over Eloquent because Eloquent does not support composite primary keys
	private function updateWaterAdditive($aquariumLogID)
	{
		// First see if an additive was selected
		if(Input::get('waterAdditive') != '')
 			if(Input::get('waterAdditive') != 0)
			{
				// See if it exists in the DB
				$additiveLog = DB::table('WaterAdditiveLogs')
					->where('aquariumLogID', '=', $aquariumLogID)
					->where('waterAdditiveID', '=', Input::get('waterAdditive'))	
					->first();
				if(isset($additiveLog))
				{
					// Now see if we need to update the additive, or delete the entry
					if(Input::get('waterAdditiveAmount') > 0)
					{
						// There is probably a better way to do this, but convert the standard DB object above into an array
						// and include the amount
						$additiveLog = array('aquariumLogID' => $additiveLog->aquariumLogID,
							'waterAdditiveID' => $additiveLog->waterAdditiveID,
							'amount' => Input::get('waterAdditiveAmount'));
						DB::table('WaterAdditiveLogs')
							->where('aquariumLogID', '=', $aquariumLogID)
							->where('waterAdditiveID', '=', Input::get('waterAdditive'))
							->update($additiveLog);
					}
					elseif(Input::get('waterAdditiveAmount') == 0)
					{
						DB::table('WaterAdditiveLogs')
							->where('aquariumLogID', '=', $aquariumLogID)
							->where('waterAdditiveID', '=', Input::get('waterAdditive'))
							->delete();
					}
				}
				// Otherwise, let's add a new entry
				elseif(Input::get('waterAdditiveAmount') > 0)
				{
					DB::table('WaterAdditiveLogs')->insert(
					    array('aquariumLogID' => $aquariumLogID, 
							  'waterAdditiveID' => Input::get('waterAdditive'),
							  'amount' => Input::get('waterAdditiveAmount')));
				}
				return 'Additive Added';
			}
	}
	
	// We had to use DB::table here over Eloquent because Eloquent does not support composite primary keys
	private function updateEquipmentLog($aquariumLogID)
	{
		// First see if an equipment was selected
		if(Input::get('equipment') != '')
 			if(Input::get('equipment') != 0)
			{
				// Now check whether it was a maintenance
				if(Input::get('equipmentMaintenance'))
					$maintenance = 'Yes';
				else
					$maintenance = 'No';
				
				// See if it exists in the DB
				$equipmentLog = DB::table('EquipmentLogs')
					->where('aquariumLogID', '=', $aquariumLogID)
					->where('equipmentID', '=', Input::get('equipment'))	
					->first();
				// If so, we update the entry
				if(isset($equipmentLog))
				{	
					// As with the WaterAdditiveLogs, this is not elegant and should be improved.
					$equipmentLog = array('aquariumLogID' => $equipmentLog->aquariumLogID,
						'maintenance' => $maintenance);
					DB::table('EquipmentLogs')
						->where('aquariumLogID', '=', $aquariumLogID)
						->where('equipmentID', '=', Input::get('equipment'))
						->update($equipmentLog);
				}
				// Otherwise, we add a new entry
				else
				{
					DB::table('EquipmentLogs')->insert(
					    array('aquariumLogID' => $aquariumLogID, 
							  'equipmentID' => Input::get('equipment'),
							  'maintenance' => $maintenance));
				}
				return 'Equipment Changes';
			}
	}
	
	private function updateFoodLog($aquariumLogID)
	{
		//First delete what's there
		DB::table('FoodLogs')->where('aquariumLogID', '=', $aquariumLogID)->delete();
		//Then check to see if food is part of input
		if(Input::get('food') != '')
 			if(Input::get('food') != 0)	
				foreach(Input::get('food') as $foodID)
					DB::table('FoodLogs')->insert(array('aquariumLogID' => $aquariumLogID, 'foodID' => $foodID));
	}
	
	private function generateLogSummary($aquariumLogID)
	{	
		// Water Test Log
		$summary = '';
		$waterLog = WaterTestLog::where('aquariumLogID', '=', $aquariumLogID)->first();
		if(isset($waterLog))
		{
			if($waterLog->temperature != '' ||
			   $waterLog->ammonia != '' ||
			   $waterLog->nitrites != '' ||
			   $waterLog->nitrates != '' ||
			   $waterLog->phosphates != '' ||
			   $waterLog->pH != '' ||
			   $waterLog->KH != '')
				$summary = 'Tested Water';
			if($waterLog->amountExchanged > 0)
			{
				if($summary != '')
					$summary .= ', ';
				$summary .= 'Exchanged Water';
			}
		}
		
		// Water Additives Log
		$waterAdditiveLog = WaterAdditiveLog::where('aquariumLogID', '=', $aquariumLogID)
			->join('WaterAdditives', 'WaterAdditives.waterAdditiveID', '=', 'WaterAdditiveLogs.waterAdditiveID')
			->get();
		if(count($waterAdditiveLog) > 0)
		{
			foreach ($waterAdditiveLog as $additive)
			{
				if($summary != '')
					$summary .= ', ';
				$summary .= 'Added '.$additive->name;
			}
		}
		
		// Equipment Log
		$equipmentLog = EquipmentLog::where('aquariumLogID', '=', $aquariumLogID)
			->join('Equipment', 'Equipment.equipmentID', '=', 'EquipmentLogs.equipmentID')
			->get();
		if(count($equipmentLog) > 0)
		{
			foreach ($equipmentLog as $equipment)
			{
				if($summary != '')
					$summary .= ', ';
				$summary .= 'Maintained '.$equipment->name;
			}
		}
		
		// Food
		$foodLog = FoodLog::where('aquariumLogID', '=', $aquariumLogID)
			->join('Food', 'Food.foodID', '=', 'FoodLogs.foodID')
			->get();
		$foodLogCount = count($foodLog);
		if($foodLogCount > 0)
		{
			if($summary != '')
				$summary .= ', ';
			$summary .= 'Fed Fish';
			
			for($count = 0; $count <= count($foodLogCount); ++$count)
			{
				$summary .= ' '.$foodLog[$count]->name;
				if($count + 1 < $foodLogCount)
					$summary .= ' &amp; ';
			}
		}
		
		return $summary;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($aquariumID)
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($aquariumID)
	{
		$food = Food::get();
		$waterAdditives = array('0' => 'None') + WaterAdditive::lists('name', 'waterAdditiveID');
		$equipment = array('0' => 'None') + Equipment::where('aquariumID', '=', $aquariumID)->lists('name', 'equipmentID');

		return View::make('aquariumlogs/editlog')
			->with('aquariumID', $aquariumID)
			->with('food', $food)
			->with('waterAdditives', $waterAdditives)
			->with('equipment', $equipment);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($aquariumID)
	{
		$log = new AquariumLog();
		$log->aquariumID = $aquariumID;
		$log->comments = Input::get('comments');
		
		$logDate = Input::get('logDate');
		if(isset($logDate))
			if($logDate != '')
				$log->logDate = Input::get('logDate');
				
		DB::beginTransaction();
		$log->save();
		$aquariumLogID = $log->aquariumLogID;
		$this->updateWaterTestLog($aquariumLogID);
		$this->updateWaterAdditive($aquariumLogID);
		$this->updateEquipmentLog($aquariumLogID);
		$log->summary = $this->generateLogSummary($aquariumLogID);
		$log->save();
		DB::commit();
		
		return Redirect::to("aquariums/$aquariumID/logs/$aquariumLogID/edit");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($aquariumID, $logID)
	{
		DB::beginTransaction();
		$log = AquariumLog::where('AquariumLogs.aquariumLogID', '=', $logID)
			->leftjoin('WaterTestLogs', 'WaterTestLogs.aquariumLogID', '=', 'AquariumLogs.aquariumLogID')
			->select('AquariumLogs.aquariumLogID', 'AquariumLogs.aquariumID', 'logDate', 
				'summary', 'comments', 'temperature', 'ammonia', 'nitrites', 'nitrates',
				'phosphates', 'pH', 'KH', 'amountExchanged')
			->first();
		$food = Food::leftjoin('FoodLogs', 'FoodLogs.foodID', '=', 'Food.foodID')
			->selectraw('Food.foodID AS foodID, name, IF(aquariumLogID, true, false) AS selected')
			->get();
		
		$waterAdditives = array('0' => 'None') + WaterAdditive::lists('name', 'waterAdditiveID');
		$equipment = array('0' => 'None') + Equipment::where('aquariumID', '=', $aquariumID)
			->lists('name', 'equipmentID');
		
		$waterAdditiveLogs = WaterAdditiveLog::where('aquariumLogID', '=', $logID)
			->join('WaterAdditives', 'WaterAdditives.waterAdditiveID', '=', 'WaterAdditiveLogs.waterAdditiveID')
			->get();
		
		$equipmentLogs = EquipmentLog::where('aquariumLogID', '=', $logID)
			->join('Equipment', 'Equipment.equipmentID', '=', 'EquipmentLogs.equipmentID')
			->get();
		
		$foodLogs = FoodLog::where('aquariumLogID', '=', $logID)
			->join('Food', 'Food.foodID', '=', 'FoodLogs.foodID')
			->get();
		
		DB::commit();

		return View::make('aquariumlogs/editlog')
			->with('aquariumID', $aquariumID)
			->with('log', $log)
			->with('food', $food)
			->with('waterAdditives', $waterAdditives)
			->with('waterAdditiveLogs', $waterAdditiveLogs)
			->with('equipmentLogs', $equipmentLogs)
			->with('equipment', $equipment)
			->with('foodLogs', $foodLogs);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $aquariumID
	 * @param  int  $aquariumLogID
	 * @return Response
	 */
	public function update($aquariumID, $aquariumLogID)
	{		
		if(Input::get('delete'))
			return $this->destroy($aquariumID, $aquariumLogID);
			
		DB::beginTransaction();
		$log = AquariumLog::where('aquariumLogID', '=', $aquariumLogID)->first();
		
		if($log->aquariumID != $aquariumID)
		{
			DB::rollback();
			return Redirect::to("aquariums");
		}
	
		$log->comments = Input::get('comments');		
		$logDate = Input::get('logDate');
		if(isset($logDate))
			if($logDate != '')
				$log->logDate = Input::get('logDate');
		$this->updateWaterTestLog($aquariumLogID);
		$this->updateWaterAdditive($aquariumLogID);
		$this->updateEquipmentLog($aquariumLogID);
		$this->updateFoodLog($aquariumLogID);
		$log->summary = $this->generateLogSummary($aquariumLogID);
		$log->save();
		DB::commit();
	
		return Redirect::to("aquariums/$aquariumID/logs/$aquariumLogID/edit");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($aquariumID, $aquariumLogID)
	{
		$log = AquariumLog::where('aquariumLogID', '=', $aquariumLogID)
			->where('aquariumID', '=', $aquariumID)
			->first();
		$log->delete();
		return Redirect::to("aquariums/$aquariumID/");
	}
	


}
