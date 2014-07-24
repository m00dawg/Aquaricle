<?php

class LifeController extends BaseController
{
	public function index()
	{
		$globalLife = Life::whereNull('userID')
			->join('LifeTypes',
				'LifeTypes.lifeTypeID', 
				'=',
				'Life.lifeTypeID')
			->orderBy('commonName')
			->get();
		
		$userLife = Life::where('userID', '=', Auth::id())
			->join('LifeTypes',
				'LifeTypes.lifeTypeID', 
				'=',
				'Life.lifeTypeID')
			->orderBy('commonName')
			->get();		
			
		return View::make('life/index')
			->with('globalLife', $globalLife)
			->with('userLife', $userLife);
	}

	public function show($lifeID)
	{
		$life = Life::where('lifeID', '=', $lifeID)
			->first();
		
		if(!$life)
			return Redirect::to('life');
		
		return View::make('life/showlife')
			->with('life', $life);
	}

	public function create()
	{
		$lifeTypes = LifeType::orderBy('lifeTypeID')
			->lists('lifeTypeName', 'lifeTypeID');

		return View::make('life/editlife')
			->with('lifeTypes', $lifeTypes);
	}
	
	public function store()
	{
		$validator = Validator::make(
			Input::all(),
			array('commonName' => 					
					"required|min:1|max:32|unique:Life,commonName,NULL,name,userID,".auth::id(),
			      'scientificName' => 'max:64',
				  'lifeType' => 'required|exists:LifeTypes,lifeTypeID')
		);
		if ($validator->fails())
		{
			return Redirect::to('life/create')
				->withInput(Input::all())
	 			->withErrors($validator);			
		}
		$life = new Life();
		$life->userID = Auth::id();
		$life->commonName = Input::get('commonName');
		if($life->scientificName)
			$life->scientificName = Input::get('scientificName');
		else
			$life->scientificName = null;
		$life->lifeTypeID = Input::get('lifeType');
		if($life->description)
			$life->description = Input::get('description');
		else
			$life->description = null;
		$life->save();
		return Redirect::to('life')
			->withErrors(array('message' => 'Life Added!'));
	}
	
	public function edit($lifeID)
	{
		$life = Life::where('lifeID', '=', $lifeID)
			->first();
		
		if(!$life)
			return Redirect::to('life');
		if($life->userID != Auth::id())
			return Redirect::to('life');
		
		$lifeTypes = LifeType::orderBy('lifeTypeID')
			->lists('lifeTypeName', 'lifeTypeID');

		return View::make('life/editlife')
			->with('life', $life)
			->with('lifeTypes', $lifeTypes);
	}
	
	public function update($lifeID)
	{
		$life = Life::where('lifeID', '=', $lifeID)
			->first();
		
		if(!$life)
			return Redirect::to('life');
		if($life->userID != Auth::id())
			return Redirect::to('life');

		if(Input::get('delete'))
		{
			$life->delete();
			return Redirect::to('life')
				->withErrors(array('message' => $life->commonName." Deleted!"));
		}
		
		$validator = Validator::make(
			Input::all(),
			array('commonName' => 					
					"required|min:1|max:32|unique:Life,".
						"commonName,$life->commonName,commonName,userID,".auth::id(),
			      'scientificName' => 'max:64',
				  'lifeType' => 'required|exists:LifeTypes,lifeTypeID')
		);
		if ($validator->fails())
		{
			return Redirect::to("life/$lifeID/edit")
				->withInput(Input::all())
	 			->withErrors($validator);			
		}
		
		$life->commonName = Input::get('commonName');
		if($life->scientificName)
			$life->scientificName = Input::get('scientificName');
		else
			$life->scientificName = null;
		$life->lifeTypeID = Input::get('lifeType');
		if($life->description)
			$life->description = Input::get('description');
		else
			$life->description = null;
		$life->save();
		return Redirect::to("life/$lifeID/edit")
			->withErrors(array('message' => 'Life Updated!'));
	}
}

?>