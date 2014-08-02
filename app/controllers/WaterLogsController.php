<?php

class WaterLogsController extends BaseController
{	
	
	public function getWaterLogs($aquariumID)
	{
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
			->paginate(20);
		DB::commit();
		return View::make('aquariumlogs/waterlogs')
			->with('aquariumID', $aquariumID)
			->with('capacity', $aquarium->capacity)
			->with('measurementUnits', $aquarium->getMeasurementUnits())
			->with('waterLogs', $waterLogs)
			->with('logDateList', $waterLogs->lists('logDate'))
			->with('ammoniaList', $waterLogs->lists('ammonia'))
			->with('nitriteList', $waterLogs->lists('nitrites'))
			->with('nitrateList', $waterLogs->lists('nitrates'));
	}
	
	/* Public Interface Functions */
	public function getPublicWaterLogs($aquariumID)
	{
		DB::beginTransaction();
		$aquarium = Aquarium::where('aquariumID', '=', $aquariumID)
			->first();
		$waterLogs = WaterTestLog::where('aquariumID', '=', $aquariumID)
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'WaterTestLogs.aquariumLogID')
			->orderBy('logDate', 'desc')
			->get();
		DB::commit();
		return View::make('public/waterlogs')
			->with('aquariumID', $aquariumID)
			->with('measurementUnits', $aquarium->getMeasurementUnits())
			->with('waterLogs', $waterLogs);
	}
}