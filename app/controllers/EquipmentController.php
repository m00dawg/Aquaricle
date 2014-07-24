<?php

class EquipmentController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($aquariumID)
	{
		$activeEquipment = Equipment::where('aquariumID', '=', $aquariumID)
			->join('EquipmentTypes', 
				'EquipmentTypes.equipmentTypeID', 
				'=', 
				'Equipment.equipmentTypeID')
			->whereNull('deletedAt')
			->select('equipmentID', 'name', 'typeName', 'purchasePrice', 'createdAt', 'maintInterval')
			->get();
				
		$inactiveEquipment = Equipment::where('aquariumID', '=', $aquariumID)
			->join('EquipmentTypes', 
				'EquipmentTypes.equipmentTypeID', 
				'=', 
				'Equipment.equipmentTypeID')
			->whereNotNull('deletedAt')
			->select('equipmentID', 'name', 'typeName', 'purchasePrice', 'createdAt', 'deletedAt', 'maintInterval')
			->get();
				
		$activeCost =  DB::table('Equipment')
			->where('aquariumID', '=', $aquariumID)
			->whereNull('deletedAt')
			->sum('purchasePrice');

		$inactiveCost =  DB::table('Equipment')
			->where('aquariumID', '=', $aquariumID)
			->whereNotNull('deletedAt')
			->sum('purchasePrice');

		$totalCost = $activeCost + $inactiveCost;

	    return View::make('equipment/index')
			->with('aquariumID', $aquariumID)
			->with('activeEquipment', $activeEquipment)
			->with('inactiveEquipment', $inactiveEquipment)
			->with('activeCost', $activeCost)
			->with('inactiveCost', $inactiveCost)	
			->with('totalCost', $totalCost);
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
		$validator = Validator::make(
			Input::all(),
			array('name' => "required|unique:Equipment,name,NULL,id,aquariumID,$aquariumID",
				  'createdAt' => 'date',
   		  		  'maintInterval' => 'integer|min:0|max:65535')
		);
		if ($validator->fails())
			return Redirect::to("aquariums/$aquariumID/equipment/create")
				->withInput(Input::all())
				->withErrors($validator);
		
		$equipment = new Equipment();
		$equipment->aquariumID = $aquariumID;
		$equipment->equipmentTypeID = Input::get('equipmentType');
		$equipment->name = Input::get('name');
		if(Input::get('createdAt') != '')
			$equipment->createdAt = strtotime(Input::get('createdAt'));
		if(Input::get('maintInterval') != '')
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
		DB::beginTransaction();
		$equipment = Equipment::where('aquariumID', '=', $aquariumID)
			->where('equipmentID', '=', $equipmentID)
			->first();
		if(!is_a($equipment, 'Equipment'))
			return Redirect::to("aquariums/");
		
		$name = $equipment->name;
		$createdAt = $equipment->createdAt;
		
		if(Input::get('delete'))
			return $this->destroy($aquariumID, $equipmentID);
		
		$validator = Validator::make(
			Input::all(),
			array('name' => "required|unique:Equipment,name,$name,name,aquariumID,$aquariumID",
				  'purchasePrice' => 'numeric|min:0|max:9999.99',
				  'url' => 'url|max:255',
   		  		  'maintInterval' => 'integer|min:1|max:65535',
				  'createdAt' => 'date',
				  'deletedAt' => "after:$createdAt|date"
			  )
		);
		if ($validator->fails())
		{
			DB::rollback();
			return Redirect::to("aquariums/$aquariumID/equipment/$equipmentID/edit")
				->withInput(Input::all())
				->withErrors($validator);
		}
		
		$equipment->equipmentTypeID = Input::get('equipmentType');
		$equipment->name = Input::get('name');
		if(Input::get('maintInterval') != '')
			$equipment->maintInterval = Input::get('maintInterval');
		else
			$equipment->maintInterval = null;
		if(Input::get('purchasePrice') != '')
			$equipment->purchasePrice = Input::get('purchasePrice');
		else
			$equipment->purchasePrice = null;
		if(Input::get('url') != '')
			$equipment->url = Input::get('url');
		else
			$equipment->url = null;
		if(Input::get('createdAt') != '')
			$equipment->createdAt = strtotime(Input::get('createdAt'));
		if(Input::get('deletedAt') != '')
			$equipment->deletedAt = strtotime(Input::get('deletedAt'));
		else
			$equipment->deletedAt = null;

		$equipment->comments = Input::get('comments');

		/*
		$log = new AquariumLog();
		$log->aquariumID = $aquariumID;
		$log->summary = 'Updated '.$equipment->name;
		*/
		$equipment->save();
		/*
		$log->save();
		$equipmentLog = new EquipmentLog();
		$equipmentLog->aquariumLogID = $log->aquariumLogID;
		$equipmentLog->equipmentID = $equipment->equipmentID;
		$equipmentLog->maintenance = 'No';
		$equipmentLog->save();
		*/
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
