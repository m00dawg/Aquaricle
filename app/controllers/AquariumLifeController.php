<?php

class AquariumLifeController extends BaseController
{
	public function index($aquariumID)
	{	
		$currentLife = AquariumLife::where('aquariumID', '=', $aquariumID)
			->join('Life', 'Life.lifeID', '=', 'AquariumLife.lifeID')
			->whereNull('deletedAt')
			->get();
		$formerLife = AquariumLife::where('aquariumID', '=', $aquariumID)
			->join('Life', 'Life.lifeID', '=', 'AquariumLife.lifeID')
			->whereNotNull('deletedAt')
			->get();
		
		$currentCost =  DB::table('AquariumLife')
			->where('aquariumID', '=', $aquariumID)
			->whereNull('deletedAt')
			->sum('purchasePrice');

		$formerCost =  DB::table('Equipment')
			->where('aquariumID', '=', $aquariumID)
			->whereNotNull('deletedAt')
			->sum('purchasePrice');
			
		
		return View::make('aquariums/life/index')
			->with('aquariumID', $aquariumID)
			->with('currentLife', $currentLife)
			->with('formerLife', $formerLife);
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
		
		$log = new AquariumLog();
		$log->aquariumID = $aquariumID;	
		$log->summary = 'Added '.$life->qty.' '.$life->Life->commonName.' To Aquarium';
		$log->save();
		
		$lifeLog = new LifeLog();
		$lifeLog->aquariumLogID = $log->aquariumLogID;
		$lifeLog->lifeID = $life->lifeID;
		$lifeLog->save();
		
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
		$life = AquariumLife::where('aquariumID', '=', $aquariumID)
			->where('AquariumLife.aquariumLifeID', '=', $aquariumLifeID)
			->first();
		if(!$life)
			return Redirect::to("aquariums/$aquariumID/life/");
		
		if(Input::get('delete'))
		{
			if($life->nickname)
				$name = $life->nickname;
			else
				$name = $life->Life->commonName;
			$life->delete();
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
		if(Input::get('deletedAt'))
			$life->deletedAt = Input::get('deletedAt');	
		else
			$life->deletedAt = null;	
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
		
		return Redirect::to("aquariums/$aquariumID/life/$aquariumLifeID/edit")
			->withInput(Input::all())
 			->withErrors(array('message' => 'Life Updated!'));	
	}
}