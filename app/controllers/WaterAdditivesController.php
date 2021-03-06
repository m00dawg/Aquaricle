<?php

class WaterAdditivesController extends BaseController
{
	public function index($aquariumID)
	{
		$waterAdditives = WaterAdditive::select(DB::raw(
			'WaterAdditives.waterAdditiveID AS waterAdditiveID,
			WaterAdditives.name AS name,
			MAX(AquariumLogs.logDate) AS lastAdded,
			DATEDIFF(UTC_TIMESTAMP(), MAX(AquariumLogs.logDate)) AS daysSince,
			amount'))
			->join('WaterAdditiveLogs', 
				'WaterAdditiveLogs.waterAdditiveID', '=', 'WaterAdditives.waterAdditiveID')
			->join('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'WaterAdditiveLogs.aquariumLogID')
			->where('AquariumLogs.aquariumID', '=', $aquariumID)
			->groupby('WaterAdditives.waterAdditiveID')
			->orderby('daysSince', 'desc')
			->get();
			
		return View::make('wateradditives/index')
			->with('aquariumID', $aquariumID)
			->with('waterAdditives', $waterAdditives);
	}	

	public function getWaterAdditive($aquariumID, $waterAdditiveID)
	{
		$waterAdditive = WaterAdditive::where('waterAdditiveID', '=', $waterAdditiveID)
			->first();
		$logs = WaterAdditiveLog::where('waterAdditiveID', '=', $waterAdditiveID)
			->join('AquariumLogs', 
				'AquariumLogs.aquariumLogID', '=', 'WaterAdditiveLogs.aquariumLogID')
			->where('AquariumLogs.aquariumID', '=', $aquariumID)
			->orderby('logdate', 'desc')
			->paginate(20);
		return View::make('wateradditives/wateradditive')
			->with('aquariumID', $aquariumID)
			->with('waterAdditive', $waterAdditive)
			->with('logs', $logs);
	}
}