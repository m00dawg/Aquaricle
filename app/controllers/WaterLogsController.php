<?php

class WaterLogsController extends BaseController
{	
	
	public function getWaterLogs($aquariumID)
	{
		$numEntries = 20;
		DB::beginTransaction();
		$aquarium = Aquarium::where('aquariumID', '=', $aquariumID)
			->first();
		if(!$aquarium)
			return Redirect::to("/");
		$waterLogs = WaterTestLog::select(DB::raw('WaterTestLogs.aquariumLogID, 
			logDate, temperature,
			ammonia, nitrites, nitrates, phosphates, pH, KH, GH, TDS,
			amountExchanged,
			ROUND((amountExchanged / capacity) * 100, 0) AS changePct'))
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'WaterTestLogs.aquariumLogID')
			->join('Aquariums', 'Aquariums.aquariumID', '=', 'AquariumLogs.aquariumID')
			->where('Aquariums.aquariumID', '=', $aquariumID)
			->orderBy('logDate', 'desc')
			->paginate($numEntries);
	
		$cycleData = WaterTestLog::selectRaw('DATE(logDate) AS logDate,
				ammonia, nitrites, nitrates')
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', 
				'=', 'WaterTestLogs.aquariumLogID')
			->where('AquariumLogs.aquariumID', '=', $aquariumID)
			->whereNotNull('ammonia')
			->whereNotNull('nitrites')
			->whereNotNull('nitrates')
			->orderBy('logDate', 'asc')
			->paginate($numEntries);
			
		$phosphates = WaterTestLog::selectRaw('DATE(logDate) AS logDate, phosphates')
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', 
				'=', 'WaterTestLogs.aquariumLogID')
			->where('AquariumLogs.aquariumID', '=', $aquariumID)
			->whereNotNull('phosphates')
			->orderBy('logDate', 'asc')
			->paginate($numEntries);
			
		$waterExchanged = WaterTestLog::selectRaw('DATE(logDate) AS logDate, amountExchanged')
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', 
				'=', 'WaterTestLogs.aquariumLogID')
			->where('AquariumLogs.aquariumID', '=', $aquariumID)
			->whereNotNull('amountExchanged')
			->orderBy('logDate', 'asc')
			->paginate($numEntries);
			
		DB::commit();
		return View::make('aquariumlogs/waterlogs')
			->with('aquariumID', $aquariumID)
			->with('capacity', $aquarium->capacity)
			->with('measurementUnits', $aquarium->getMeasurementUnits())
			->with('waterLogs', $waterLogs)
			->with('cycleLogDateList', $cycleData->lists('logDate'))
			->with('ammoniaList', $cycleData->lists('ammonia'))
			->with('nitriteList', $cycleData->lists('nitrites'))
			->with('nitrateList', $cycleData->lists('nitrates'))
			->with('phoshateLogDateList', $phosphates->lists('logDate'))
			->with('phoshateDataList', $phosphates->lists('phosphates'))
			->with('waterChangeDateList', $waterExchanged->lists('logDate'))
			->with('waterChangeDataList', $waterExchanged->lists('amountExchanged'));
	}
	
	/* Public Interface Functions */
	public function getPublicWaterLogs($aquariumID)
	{
		$numEntries = 20;
		
		DB::beginTransaction();
		
		$aquarium = Aquarium::where('aquariumID', '=', $aquariumID)
			->first();
		$waterLogs = WaterTestLog::where('aquariumID', '=', $aquariumID)
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'WaterTestLogs.aquariumLogID')
			->orderBy('logDate', 'desc')
			->get();
		
		$cycleData = WaterTestLog::selectRaw('DATE(logDate) AS logDate,
				ammonia, nitrites, nitrates')
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', 
				'=', 'WaterTestLogs.aquariumLogID')
			->where('AquariumLogs.aquariumID', '=', $aquariumID)
			->whereNotNull('ammonia')
			->whereNotNull('nitrites')
			->whereNotNull('nitrates')
			->orderBy('logDate', 'asc')
			->paginate($numEntries);
			
		$phosphates = WaterTestLog::selectRaw('DATE(logDate) AS logDate, phosphates')
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', 
				'=', 'WaterTestLogs.aquariumLogID')
			->where('AquariumLogs.aquariumID', '=', $aquariumID)
			->whereNotNull('phosphates')
			->orderBy('logDate', 'asc')
			->paginate($numEntries);
			
		$waterExchanged = WaterTestLog::selectRaw('DATE(logDate) AS logDate, amountExchanged')
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', 
				'=', 'WaterTestLogs.aquariumLogID')
			->where('AquariumLogs.aquariumID', '=', $aquariumID)
			->whereNotNull('amountExchanged')
			->orderBy('logDate', 'asc')
			->paginate($numEntries);
		
		DB::commit();
		return View::make('public/waterlogs')
			->with('aquariumID', $aquariumID)
			->with('measurementUnits', $aquarium->getMeasurementUnits())
			->with('waterLogs', $waterLogs)
			->with('cycleLogDateList', $cycleData->lists('logDate'))
			->with('ammoniaList', $cycleData->lists('ammonia'))
			->with('nitriteList', $cycleData->lists('nitrites'))
			->with('nitrateList', $cycleData->lists('nitrates'))
			->with('phoshateLogDateList', $phosphates->lists('logDate'))
			->with('phoshateDataList', $phosphates->lists('phosphates'))
			->with('waterChangeDateList', $waterExchanged->lists('logDate'))
			->with('waterChangeDataList', $waterExchanged->lists('amountExchanged'));
	}
}