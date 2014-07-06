<?php

class EquipmentController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($aquariumID)
	{
		$equipment = Equipment::where('aquariumID', '=', $aquariumID)
			->join('EquipmentTypes', 
				'EquipmentTypes.equipmentTypeID', 
				'=', 
				'Equipment.equipmentTypeID')
			->get();
		/*
		$equipment = Equipment::select(DB::raw(
				'Equipment.equipmentID, Equipment.name, 
				Equipment.createdAt, Equipment.deletedAt,
				Equipment.maintInterval,
				Equipment.comments,
				EquipmentTypes.typeName,
				MAX(AquariumLogs.logDate) AS lastMaint,
				DATEDIFF(UTC_TIMESTAMP(), MAX(AquariumLogs.logDate)) AS daysSinceMaint,
				CAST(Equipment.maintInterval AS signed) - DATEDIFF(UTC_TIMESTAMP(), 
				MAX(AquariumLogs.logDate)) AS nextMaintDays'))
			->join('EquipmentTypes', 'EquipmentTypes.equipmentTypeID', '=', 'Equipment.equipmentTypeID')
			->leftjoin('EquipmentLogs', 'EquipmentLogs.equipmentID', '=', 'Equipment.equipmentID')
			->leftjoin('AquariumLogs', 'AquariumLogs.aquariumLogID', '=', 'EquipmentLogs.aquariumLogID')
			->where('Equipment.aquariumID', '=', $aquariumID)
			->groupby('Equipment.equipmentID')
			->orderby('nextMaintDays', 'desc')
			->get();
		*/
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
		$equipmentTypes = EquipmentType::lists('typeName', 'equipmentTypeID');
	    return View::make('equipment/editequipment')
			->with('aquariumID', $aquariumID)
			->with('equipmentTypes', $equipmentTypes);
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
		$equipment->equipmentTypeID = Input::get('equipmentType');
		$equipment->name = Input::get('name');
		if(Input::get('createdAt') != '')
			$equipment->createdAt = Input::get('createdAt');
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
			->join('EquipmentTypes', 
				'EquipmentTypes.equipmentTypeID', 
				'=', 
				'Equipment.equipmentTypeID')
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
			->orderby('logDate', 'desc')
			->paginate(20);
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
		$equipmentTypes = EquipmentType::lists('typeName', 'equipmentTypeID');
		$equipment = Equipment::where('aquariumID', '=', $aquariumID)
			->where('equipmentID', '=', $equipmentID)
			->first();
		if(!is_a($equipment, 'Equipment'))
			return Redirect::to("aquariums/");		
	    return View::make('equipment/editequipment')
			->with('aquariumID', $aquariumID)
			->with('equipmentTypes', $equipmentTypes)
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
		$equipment->equipmentTypeID = Input::get('equipmentType');
		$equipment->name = Input::get('name');
		$equipment->createdAt = Input::get('createdAt');
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
