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
		
	    return View::make('equipment/equipment')
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
			$equipment->installedOn = Input::get('installedOn');
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
		return Redirect::to("aquariums/$aquariumID/equipment/$equipmentID/edit");
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
		$equipment->installedOn = Input::get('installedOn');
		$equipment->removedOn = Input::get('removedOn');
		$equipment->maintInterval = Input::get('maintInterval');
		$equipment->comments = Input::get('comments');

		$log = new AquariumLog();
		$log->aquariumID = $aquariumID;
		$log->summary = 'Removed '.$equipment->name;
		
		DB::beginTransaction();
		$equipment->save();
		$log->save();
		$equipmentLog = new EquipmentLog();
		$equipmentLog->aquariumLogID = $log->aquariumLogID;
		$equipmentLog->equipmentID = $equipment->equipmentID;
		$equipmentLog->maintenance = 'Yes';
		$equipmentLog->save();	
		DB::commit();
		
		return Redirect::to("aquariums/$aquariumID/equipment/$equipmentID/edit");
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
