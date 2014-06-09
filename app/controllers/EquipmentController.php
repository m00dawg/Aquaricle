<?php

class EquipmentController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($aquariumID)
	{
		$equipment = Equipment::where('aquariumID', '=', $aquariumID)->get();
		
	    return View::make('equipment/equipmentindex')
			->with('aquariumID', $aquariumID)
			->with('equipment', $equipment);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($aquariumID)
	{
	    return View::make('equipment/editequipment')
			->with('aquariumID', $aquariumID);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($aquariumID)
	{
		$equipment = new Equipment();
		$equipment->aquariumID = $aquariumID;
		$equipment->name = Input::get('name');
		if(Input::get('installedOn') != '')
			$equipment->createdAt = Input::get('installedOn');
		$equipment->maintInterval = Input::get('maintInterval');
		$equipment->comments = Input::get('comments');
		
		DB::beginTransaction();
		$equipment->save();
		$equipmentID = $equipment->equipmentID;
		$log = new AquariumLog();
		$log->aquariumID = $aquariumID;
		$log->summary = 'Installed '.$equipment->name;
		$log->save();
	
		$equipmentLog = new EquipmentLog();
		$equipmentLog->aquariumLogID = $log->aquariumLogID;
		$equipmentLog->equipmentID = $equipmentID;
		$equipmentLog->maintenance = 'Yes';
		$equipmentLog->save();
		
		DB::commit();
		return Redirect::to("aquariums/$aquariumID/equipment/$equipmentID");
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($aquariumID, $equipmentID)
	{
		DB::beginTransaction();
		$equipment = Equipment::where('aquariumID', '=', $aquariumID)
			->where('equipmentID', '=', $equipmentID)
			->first();
		if(!is_a($equipment, 'Equipment'))
		{
			DB::rollback();
			return Redirect::to("aquariums/");
		}
		$logs = AquariumLog::where('aquariumID', '=', $aquariumID)
			->join('EquipmentLogs', 'EquipmentLogs.aquariumLogID', '=', 'AquariumLogs.aquariumLogID')
			->where('equipmentID', '=', $equipmentID)
			->get();
	    return View::make('equipment/showequipment')
			->with('aquariumID', $aquariumID)
			->with('equipment', $equipment)
			->with('logs', $logs);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($aquariumID, $equipmentID)
	{
		$equipment = Equipment::where('aquariumID', '=', $aquariumID)
			->where('equipmentID', '=', $equipmentID)
			->first();
		if(!is_a($equipment, 'Equipment'))
			return Redirect::to("aquariums/");		
	    return View::make('equipment/editequipment')
			->with('aquariumID', $aquariumID)
			->with('equipment', $equipment);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($aquariumID, $equipmentID)
	{
		if(Input::get('delete'))
			return $this->destroy($aquariumID, $equipmentID);
		
		$equipment = Equipment::where('aquariumID', '=', $aquariumID)
			->where('equipmentID', '=', $equipmentID)
			->first();
		if(!is_a($equipment, 'Equipment'))
			return Redirect::to("aquariums/");
		$equipment->name = Input::get('name');
		$equipment->createdAt = Input::get('installedOn');
		if(Input::get('removedOn') != '')
			$equipment->removedOn = Input::get('removedOn');
		$equipment->maintInterval = Input::get('maintInterval');
		$equipment->comments = Input::get('comments');

		$log = new AquariumLog();
		$log->aquariumID = $aquariumID;
		$log->summary = 'Updated '.$equipment->name;
		
		DB::beginTransaction();
		$equipment->save();
		$log->save();
		$equipmentLog = new EquipmentLog();
		$equipmentLog->aquariumLogID = $log->aquariumLogID;
		$equipmentLog->equipmentID = $equipment->equipmentID;
		$equipmentLog->maintenance = 'Yes';
		$equipmentLog->save();	
		DB::commit();
		
		return Redirect::to("aquariums/$aquariumID/equipment/$equipmentID");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($aquariumID, $equipmentID)
	{
		$equipment = Equipment::where('aquariumID', '=', $aquariumID)
			->where('equipmentID', '=', $equipmentID)
			->first();
		$equipment->delete();
		return Redirect::to("aquariums/$aquariumID/equipment");
	}


}
