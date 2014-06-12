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
			->get();
		
		$equipment = Equipment::select(DB::raw(
				'Equipment.equipmentID, Equipment.name, 
				MAX(AquariumLogs.logDate) AS lastMaint,
				DATEDIFF(UTC_TIMESTAMP(), MAX(AquariumLogs.logDate)) AS daysSinceMaint,
				Equipment.maintInterval - DATEDIFF(UTC_TIMESTAMP(), MAX(AquariumLogs.logDate)) AS nextMaintDays'))
			->leftjoin('EquipmentLogs', 'EquipmentLogs.equipmentID', '=', 'Equipment.equipmentID')
			->leftjoin('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'EquipmentLogs.aquariumLogID')
			->where('Equipment.aquariumID', '=', $aquariumID)
			->whereNotNull('maintInterval')
			->whereNull('Equipment.deletedAt')
			->groupby('Equipment.equipmentID')
			->orderby('nextMaintDays', 'desc')
			->get();
			
		$lastWaterChange = DB::table('Aquariums')
			->select(DB::raw('logDate, DATEDIFF(NOW(), logDate) AS daysSince,
				amountExchanged, ROUND((amountExchanged / capacity) * 100, 0) AS changePct'))
			->join('AquariumLogs', 'AquariumLogs.aquariumID', '=', 'Aquariums.aquariumID')
			->join('WaterTestLogs', 'WaterTestLogs.aquariumLogID', '=', 'AquariumLogs.aquariumLogID')
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
			->with('equipment', $equipment)
			->with('measurementUnits', $aquarium->getMeasurementUnits());
	}
	
	public function create()
	{
		return View::make('aquariums/modifyaquarium');
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

		return View::make('aquariums/modifyaquarium')
			->with('aquarium', $aquarium);
	}
	
	public function update($aquariumID)
	{
		$aquarium = Aquarium::singleAquarium($aquariumID);
		$aquarium->name = Input::get('name');
		$aquarium->location = Input::get('location');
		$aquarium->measurementUnits = Input::get('measurementUnits');
		$aquarium->capacity = Input::get('capacity');
		$aquarium->length = Input::get('length');
		$aquarium->width = Input::get('width');
		$aquarium->height = Input::get('height');
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
	
	
}
	
?>
