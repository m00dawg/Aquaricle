<?php

class AquariumLogsController extends BaseController {


	private function addWaterLog($aquariumLogID)
	{
		if(Input::get('temperature') != '' ||
		   Input::get('ammonia') != '' ||
		   Input::get('nitrites') != '' ||
		   Input::get('nitrates') != '' ||
		   Input::get('phosphates') != '' ||
		   Input::get('pH') != '' ||
		   Input::get('KH') != '' ||
		   Input::get('ammountExchanged') != '')
		{
			$waterTestLog = new WaterTestLog();
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
			if(Input::get('ammountExchanged') != '')
				$waterTestLog->KH = Input::get('ammountExchanged');
			
			$waterTestLog->save();
		}
	}
	
	public function addWaterAdditive($aquariumLogID)
	{
		if(Input::get('waterAdditive') != '')
		{
			$additive = new WaterAdditiveLog();
			$additive->aquariumLogID = $aquariumLogID;
			$additive->waterAdditiveID = Input::get('waterAdditive');
			$additive->amount = Input::get('waterAdditiveAmount');
			$additive->save();
		}
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
		$waterAdditives = WaterAdditive::lists('name', 'waterAdditiveID');
		return View::make('aquariumlogs/editlog')
			->with('aquariumID', $aquariumID)
			->with('waterAdditives', $waterAdditives);
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
		$this->addWaterLog($aquariumLogID);
		$this->addWaterAdditive($aquariumLogID);
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
			->leftjoin('WaterTestLogs', 'waterTestLogs.aquariumLogID', '=', 'aquariumLogs.aquariumLogID')
			->first();
		$waterAdditives = WaterAdditive::lists('name', 'waterAdditiveID');
		$waterAdditiveLogs = WaterAdditiveLog::where('aquariumLogID', '=', $logID)
			->join('WaterAdditives', 'WaterAdditives.waterAdditiveID', '=', 'WaterAdditiveLogs.waterAdditiveID')
			->get();
		DB::commit();

		return View::make('aquariumlogs/editlog')
			->with('aquariumID', $aquariumID)
			->with('log', $log)
			->with('waterAdditives', $waterAdditives)
			->with('waterAdditiveLogs', $waterAdditiveLogs);
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
		DB::beginTransaction();
		
		$log = AquariumLog::where('aquariumLogID', '=', $aquariumLogID)->first();
		
		if($log->aquariumID != $aquariumID)
			return Redirect::to("aquariums");
		
		$log->comments = Input::get('comments');
		
		$logDate = Input::get('logDate');
		if(isset($logDate))
			if($logDate != '')
				$log->logDate = Input::get('logDate');
		
		$log->save();
		$this->addWaterLog($aquariumLogID);
		$this->addWaterAdditive($aquariumLogID);
		DB::commit();
	
		return Redirect::to("aquariums/$aquariumID/logs/$aquariumLogID/edit");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	


}
