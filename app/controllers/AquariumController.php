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
		
		$equipment = Equipment::byLastMaintenance($aquariumID);

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
		$userID = Auth::user()->userID;

		$validator = Validator::make(
			Input::all(),
			array('name' => "required|unique:Aquariums,name,NULL,id,userID,$userID",
					'location' => 'max:48',
					'visibility' => 'in:Public,Private',
					'measurementUnits' => 'in:Metric,Imperial',
					'capacity' => 'required|numeric|min:0|max:999.99',
					'length' => 'required|numeric|min:0|max:999.99',
					'width' => 'required|numeric|min:0|max:999.99',
					'height' => 'required|numeric|min:0|max:999.99',
					'waterChangeInterval' => 'required|integer|min:0|max:255',
					'targetTemperature' => 'required|numeric|min:0|max:999.9',
					'targetPH' => 'required|numeric|min:0|max:99.9',
					'targetKH' => 'required|integer|min:0|max:255',
					'aquariduinoHostname' => 'max:255'
					)
		);
		if ($validator->fails())
			return Redirect::to('aquariums/create')
				->withInput(Input::all())
				->withErrors($validator);


		$aquarium = new Aquarium(Input::all());
		$aquarium->userID = $userID;
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
		$userID = Auth::user()->userID;
		$aquarium = Aquarium::singleAquarium($aquariumID);
		$name = $aquarium->name;
				
		$validator = Validator::make(
			Input::all(),
			array('name' => "required|unique:Aquariums,name,$name,name,userID,$userID",
					'location' => 'max:48',
					'visibility' => 'in:Public,Private',
					'measurementUnits' => 'in:Metric,Imperial',
					'capacity' => 'required|numeric|min:0|max:999.99',
					'length' => 'required|numeric|min:0|max:999.99',
					'width' => 'required|numeric|min:0|max:999.99',
					'height' => 'required|numeric|min:0|max:999.99',
					'waterChangeInterval' => 'required|integer|min:0|max:255',
					'targetTemperature' => 'required|numeric|min:0|max:999.9',
					'targetPH' => 'required|numeric|min:0|max:99.9',
					'targetKH' => 'required|integer|min:0|max:255',
					'aquariduinoHostname' => 'max:255'
					)
		);
		if ($validator->fails())
			return Redirect::to("aquariums/$aquariumID/edit")
				->withInput(Input::all())
				->withErrors($validator);
		
		
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
		
		$equipment = Equipment::byLastMaintenance($aquariumID);

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
