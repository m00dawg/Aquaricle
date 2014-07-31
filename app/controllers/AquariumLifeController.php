<?php

class AquariumLifeController extends BaseController
{
	
	// Constants for the helper function to generate the log
	const ADD = 0;
	const REMOVE = 1;
	const READD = 2;
	
	private function generateLog($life, $action)
	{
		$log = new AquariumLog();
		$log->aquariumID = $life->aquariumID;
		
		if ($life->nickname)
			$name = $life->nickname;
		else
			$name = $life->Life->commonName;
		if($life->qty > 1)
		{
			$qty = ' '.$life->qty;
			$name = str_plural($name);
		}
		else
			$qty = '';
		
		switch ($action)
		{
			case self::ADD:
			{
				$log->summary = "Added".$qty." $name To Aquarium";
				break;
			}
			case self::REMOVE:
			{
				$log->summary = "Removed".$qty." $name From Aquarium";
				break;
			}
			case self::READD:
			{
				$log->summary = "Re-Added".$qty." $name To Aquarium";
				break;
			}
		}		
		$log->save();
		
		$aquariumLifeLog = new AquariumLifeLog();
		$aquariumLifeLog->aquariumLogID = $log->aquariumLogID;
		$aquariumLifeLog->aquariumLifeID = $life->aquariumLifeID;
		$aquariumLifeLog->save();
	}
	
	public function index($aquariumID)
	{	
		$currentLife = AquariumLife::where('aquariumID', '=', $aquariumID)
			->join('Life', 'Life.lifeID', '=', 'AquariumLife.lifeID')
			->whereNull('deletedAt')
			->orderBy('nickname')
			->get();
		$formerLife = AquariumLife::where('aquariumID', '=', $aquariumID)
			->join('Life', 'Life.lifeID', '=', 'AquariumLife.lifeID')
			->whereNotNull('deletedAt')
			->orderBy('nickname')
			->get();
		
		$currentSummary =  DB::table('AquariumLife')
			->where('aquariumID', '=', $aquariumID)
			->whereNull('deletedAt')
			->selectRaw('SUM(purchasePrice) AS totalPrice, SUM(qty) AS totalQty')
			->first();

		$formerSummary =  DB::table('AquariumLife')
			->where('aquariumID', '=', $aquariumID)
			->whereNotNull('deletedAt')
			->selectRaw('SUM(purchasePrice) AS totalPrice, SUM(qty) AS totalQty')
			->first();
		
		return View::make('aquariums/life/index')
			->with('aquariumID', $aquariumID)
			->with('currentLife', $currentLife)
			->with('currentSummary', $currentSummary)
			->with('formerLife', $formerLife)
			->with('formerSummary', $formerSummary);
	}
	
	public function show($aquariumID, $aquariumLifeID)
	{
		$life = AquariumLife::where('aquariumID', '=', $aquariumID)
			->where('aquariumLifeID', '=', $aquariumLifeID)
			->first();
		if(!$life)
			return Redirect::to("aquariums/$aquariumID/life/");
			
		return View::make('aquariums/life/show')
			->with('aquariumID', $aquariumID)
			->with('life', $life);
	}
	

	public function create($aquariumID)
	{
		$lifeList = Life::orderBy('commonName')
			->selectraw("IF(scientificName IS NOT NULL,
				CONCAT(commonName, ' (', scientificName, ')'),
				commonName) AS name, lifeID")
			->lists('name', 'lifeID');
		return View::make('aquariums/life/edit')
			->with('aquariumID', $aquariumID)
			->with('lifeList', $lifeList);
	}
	
	public function store($aquariumID)
	{
		$validator = Validator::make(
			Input::all(),
			array('nickname' => 'min:1|max:48',
			      'lifeID' => 'required|exists:Life,lifeID',
				  'createdAt' => 'date',
				  'qty' => 'required|integer|min:1|max:255',
				  'purchasePrice' => 'numeric|min:0|max:9999.99',
				  'purchasedAt' => 'min:1|max:64')
		);
		if ($validator->fails())
		{
			return Redirect::to("aquariums/$aquariumID/life/create")
				->withInput(Input::all())
	 			->withErrors($validator);	
		}	
		
		DB::beginTransaction();
		
		$life = new AquariumLife();
		$life->aquariumID = $aquariumID;
		$life->lifeID = Input::get('lifeID');
		$life->qty = Input::get('qty');
		if(Input::get('nickname'))
			$life->nickname = Input::get('nickname');
		else
			$life->nickname = null;
		
		if(Input::get('createdAt'))
			$life->createdAt = Input::get('createdAt');
		if(Input::get('purchasePrice'))
			$life->purchasePrice = Input::get('purchasePrice');
		else
			$life->purchasePrice = null;
		if(Input::get('purchasedAt'))
			$life->purchasedAt = Input::get('purchasedAt');
		else
			$life->purchasedAt = null;
		if(Input::get('comments'))
			$life->comments = Input::get('comments');
		else
			$life->comments = null;
		$life->save();
		
		$this->generateLog($life, self::ADD);
		
		DB::commit();
		
		return Redirect::to("aquariums/$aquariumID/life/")
			->withInput(Input::all())
 			->withErrors(array('message' => 'Life Added!'));	
	}
	
	public function edit($aquariumID, $aquariumLifeID)
	{
		$life = AquariumLife::where('aquariumID', '=', $aquariumID)
			->where('AquariumLife.aquariumLifeID', '=', $aquariumLifeID)
			->first();
		if(!$life)
			return Redirect::to("aquariums/$aquariumID/life/");
	
		$lifeList = Life::orderBy('commonName')
			->selectraw("IF(scientificName IS NOT NULL,
				CONCAT(commonName, ' (', scientificName, ')'),
				commonName) AS name, lifeID")
			->lists('name', 'lifeID');
		
		return View::make('aquariums/life/edit')
			->with('aquariumID', $aquariumID)
			->with('lifeList', $lifeList)
			->with('life', $life);
	}
	
	public function update($aquariumID, $aquariumLifeID)
	{
		DB::beginTransaction();
		$life = AquariumLife::where('aquariumID', '=', $aquariumID)
			->where('AquariumLife.aquariumLifeID', '=', $aquariumLifeID)
			->first();
		if(!$life)
		{
			DB::rollback();
			return Redirect::to("aquariums/$aquariumID/life/");
		}
		
		if(Input::get('delete'))
		{
			if($life->nickname)
				$name = $life->nickname;
			else
				$name = $life->Life->commonName;
			$life->delete();
			DB::commit();
			return Redirect::to('aquariums/$aquariumID/life')
				->withErrors(array('message' => "$name Deleted!"));
		}
		
		$validator = Validator::make(
			Input::all(),
			array('nickname' => 'min:1|max:48',
			      'lifeID' => 'required|exists:Life,lifeID',
				  'qty' => 'required|integer|min:1|max:255',
				  'createdAt' => 'date',
				  'deletedAt' => "after:".$life->createdAt."|date",
				  'purchasePrice' => 'numeric|min:0|max:9999.99',
				  'purchasedAt' => 'min:1|max:64')
		);
		if ($validator->fails())
		{
			DB::rollback();
			return Redirect::to("aquariums/$aquariumID/life/$aquariumLifeID/edit")
				->withInput(Input::all())
	 			->withErrors($validator);	
		}	
		
		$life->lifeID = Input::get('lifeID');
		$life->qty = Input::get('qty');
		if(Input::get('nickname'))
			$life->nickname = Input::get('nickname');
		else
			$life->nickname = null;
		if(Input::get('createdAt'))
			$life->createdAt = Input::get('createdAt');
		if(Input::get('purchasePrice'))
			$life->purchasePrice = Input::get('purchasePrice');
		else
			$life->purchasePrice = null;
		if(Input::get('purchasedAt'))
			$life->purchasedAt = Input::get('purchasedAt');
		else
			$life->purchasedAt = null;
		if(Input::get('comments'))
			$life->comments = Input::get('comments');
		else
			$life->comments = null;
		
		if(Input::get('deletedAt'))
		{
			// Only update the log if this update is when the life was removed.
			// Otherwise, every update will create a new log entry.
			if (!$life->deletedAt)
				$this->generateLog($life, self::REMOVE);
			$life->deletedAt = Input::get('deletedAt');	
		}
		else
		{
			// If deletedAt was blanked out, it means the life was re-added to the tank.
			// So we want to make a log entry for that.
			if($life->deletedAt)
				$this->generateLog($life, self::READD);	
			$life->deletedAt = null;
		}
		$life->save();
		DB::commit();
		
		return Redirect::to("aquariums/$aquariumID/life/$aquariumLifeID/edit")
			->withInput(Input::all())
 			->withErrors(array('message' => 'Life Updated!'));	
	}
}
