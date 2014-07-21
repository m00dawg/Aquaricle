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
		$life = Life::join('LifeTypes',
				'LifeTypes.lifeTypeID', 
				'=',
				'Life.lifeTypeID')
			->orderBy('commonName')
			->get();
		
		$lifeTypes = LifeType::orderBy('lifeTypeID')
			->lists('lifeTypeName', 'lifeTypeID');

		return View::make('life/editlife')
			->with('lifeTypes', $lifeTypes);
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
			array('commonName' => 						"required|min:1|max:32|unique:Life,commonName,NULL,name,userID,".auth::id(),
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
		$life->scientificName = Input::get('scientificName');
		$life->lifeTypeID = Input::get('lifeType');
		$life->description = Input::get('description');
		$life->save();
		return Redirect::to('life')
			->withErrors(array('message' => 'Life Added!'));
	}
}

?>