<?php

class AquariumLogsController extends BaseController {

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
		return View::make('aquariumlogs/editlog')
			->with('aquariumID', $aquariumID);
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
		
		$log->save();
		$aquariumLogID = $log->aquariumLogID;
		
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
		$log = AquariumLog::find($logID);
		$waterTestLog = $log->waterTestLog();
		return View::make('aquariumlogs/editlog')
			->with('aquariumID', $aquariumID)
			->with('log', $log);
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
		$log = AquariumLog::where('aquariumLogID', '=', $aquariumLogID)->first();
		
		if($log->aquariumID != $aquariumID)
			return Redirect::to("aquariums");
		
		$log->comments = Input::get('comments');
		
		$logDate = Input::get('logDate');
		if(isset($logDate))
			if($logDate != '')
				$log->logDate = Input::get('logDate');
		
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
			
			$waterTestLog->save();
		}
		
		$log->save();
		
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
