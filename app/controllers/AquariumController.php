<?php

class AquariumController extends BaseController
{	

	public function index()
	{
		return self::getAquariums();
	}

	public function getIndex()
	{
		return self::getAquariums();
	}

	public function getAquariums()
	{
		$aquariums = Aquarium::byAuthUser()->get();
	    return View::make('aquariums/aquariums')->with('aquariums', $aquariums);		
	}
	
	public function getAquarium($aquariumID)
	{
		if ($aquariumID == null)
			return Redirect::to("aquariums");
		
		// Only look at last 30 days for the Aquarium Logs
		$dateSub = new DateTime();
		$dateSub->sub(new DateInterval('P10D'));

		DB::beginTransaction();
		
		$aquarium = Aquarium::singleAquarium($aquariumID);
		
		$logs = AquariumLog::where('aquariumID', '=', $aquariumID)
			->where('logDate', '>=', $dateSub)
			->orderby('logDate', 'desc')
			->get();
		$favorites = AquariumLogFavorite::where('aquariumID', '=', $aquariumID)
			->get();
		
		$equipment = Equipment::select(DB::raw(
				'Equipment.equipmentID, Equipment.name, 
				MAX(AquariumLogs.logDate) AS lastMaint,
				DATEDIFF(UTC_TIMESTAMP(), MAX(AquariumLogs.logDate)) AS daysSinceMaint,
				CAST(Equipment.maintInterval AS signed) - DATEDIFF(UTC_TIMESTAMP(), 
				MAX(AquariumLogs.logDate)) AS nextMaintDays'))
			->leftjoin('EquipmentLogs', 'EquipmentLogs.equipmentID', '=', 'Equipment.equipmentID')
			->leftjoin('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'EquipmentLogs.aquariumLogID')
			->where('Equipment.aquariumID', '=', $aquariumID)
			->whereNotNull('maintInterval')
			->whereNull('Equipment.deletedAt')
			->groupby('Equipment.equipmentID')
			->orderby('nextMaintDays', 'desc')
			->get();

		$lastWaterChange = WaterTestLog::select(DB::raw('logDate, 
				DATEDIFF(NOW(), logDate) AS daysSince,
				CAST(waterChangeInterval AS signed) - DATEDIFF(NOW(), logDate) AS daysRemaining,
				amountExchanged, ROUND((amountExchanged / capacity) * 100, 0) AS changePct'))
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'WaterTestLogs.aquariumLogID')
			->join('Aquariums', 'Aquariums.aquariumID', '=', 'AquariumLogs.aquariumID')
			->where('Aquariums.aquariumID', '=', $aquariumID)
			->whereNotNull('amountExchanged')
			->orderby('logDate', 'desc')
			->first();
		DB::commit();
		
		return View::make('aquariums/aquarium')
			->with('aquariumID', $aquariumID)
			->with('aquarium', $aquarium)
			->with('lastWaterChange', $lastWaterChange)
			->with('logs', $logs)
			->with('favorites', $favorites)
			->with('equipment', $equipment)
			->with('measurementUnits', $aquarium->getMeasurementUnits());
	}
	
	public function create()
	{
		return View::make('aquariums/editaquarium');
	}
	
	public function store()
	{
		$aquarium = new Aquarium(Input::all());
		$aquarium->userID = Auth::user()->userID;
		$aquarium->save();
		$aquariumID = $aquarium->aquariumID;
		
		return Redirect::to("aquariums/$aquariumID/");
	}
	
	public function edit($aquariumID)
	{
		if ($aquariumID == null)
			return Redirect::to('aquariums');

		$aquarium = Aquarium::singleAquarium($aquariumID);

		return View::make('aquariums/editaquarium')
			->with('aquarium', $aquarium);
	}
	
	public function update($aquariumID)
	{
		$aquarium = Aquarium::singleAquarium($aquariumID);
		$aquarium->name = Input::get('name');
		$aquarium->location = Input::get('location');
		$aquarium->measurementUnits = Input::get('measurementUnits');
		$aquarium->visibility = Input::get('visibility');
		$aquarium->capacity = Input::get('capacity');
		$aquarium->length = Input::get('length');
		$aquarium->width = Input::get('width');
		$aquarium->height = Input::get('height');
		$aquarium->waterChangeInterval = Input::get('waterChangeInterval');
		$aquarium->targetTemperature = Input::get('targetTemperature');
		$aquarium->targetPH = Input::get('targetPH');
		$aquarium->targetKH = Input::get('targetKH');
		$aquarium->aquariduinoHostname = Input::get('aquariduinoHostname');
		$aquarium->save();
		return Redirect::to("aquariums/$aquariumID/");
	}
	
	public function destroy($aquariumID)
	{
		//
	}
	
	public function show($aquariumID)
	{
		return self::getAquarium($aquariumID);
	}
	
	public function missingMethod($parameters = array())
	{
	 	echo "Missing, goddamnit";
	}
	
	/* Public Functions */
	public function getPublicAquarium($aquariumID)
	{
		if ($aquariumID == null)
			return Redirect::to("login");

		// Only look at last 30 days for the Aquarium Logs
		$dateSub = new DateTime();
		$dateSub->sub(new DateInterval('P10D'));

		DB::beginTransaction();
		
		$aquarium = Aquarium::singleAquarium($aquariumID);
		
		if($aquarium->visibility != 'Public')
		{
			DB::rollback();
			return Redirect::to("login");
		}
		
		$logs = AquariumLog::where('aquariumID', '=', $aquariumID)
			->where('logDate', '>=', $dateSub)
			->orderby('logDate', 'desc')
			->get();
		
		$equipment = Equipment::select(DB::raw(
				'Equipment.equipmentID, Equipment.name, 
				MAX(AquariumLogs.logDate) AS lastMaint,
				DATEDIFF(UTC_TIMESTAMP(), MAX(AquariumLogs.logDate)) AS daysSinceMaint,
				CAST(Equipment.maintInterval AS signed) - DATEDIFF(UTC_TIMESTAMP(), 
				MAX(AquariumLogs.logDate)) AS nextMaintDays'))
			->leftjoin('EquipmentLogs', 'EquipmentLogs.equipmentID', '=', 'Equipment.equipmentID')
			->leftjoin('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'EquipmentLogs.aquariumLogID')
			->where('Equipment.aquariumID', '=', $aquariumID)
			->whereNotNull('maintInterval')
			->whereNull('Equipment.deletedAt')
			->groupby('Equipment.equipmentID')
			->orderby('nextMaintDays', 'desc')
			->get();

		$lastWaterChange = WaterTestLog::select(DB::raw('logDate, 
				DATEDIFF(NOW(), logDate) AS daysSince,
				CAST(waterChangeInterval AS signed) - DATEDIFF(NOW(), logDate) AS daysRemaining,
				amountExchanged, ROUND((amountExchanged / capacity) * 100, 0) AS changePct'))
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'WaterTestLogs.aquariumLogID')
			->join('Aquariums', 'Aquariums.aquariumID', '=', 'AquariumLogs.aquariumID')
			->where('Aquariums.aquariumID', '=', $aquariumID)
			->whereNotNull('amountExchanged')
			->orderby('logDate', 'desc')
			->first();
		DB::commit();
		
		return View::make('public/aquarium')
			->with('aquariumID', $aquariumID)
			->with('aquarium', $aquarium)
			->with('lastWaterChange', $lastWaterChange)
			->with('logs', $logs)
			->with('equipment', $equipment)
			->with('measurementUnits', $aquarium->getMeasurementUnits());
	}
}
	
?>
