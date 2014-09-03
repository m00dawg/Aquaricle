<?php

class AquariumLogsController extends BaseController
{

	private function uploadFile($log)
	{
		$path = public_path()."/files/".$log->aquariumID;

		if(!is_dir($path))
			mkdir($path);

		$file = Input::file('file');
		if(!$file->isValid())
			return false;

		// Kinda janky since getMimeType() doesn't seem to work
		switch(strtolower($file->getClientOriginalExtension()))
		{
			case 'jpg':
			{
				$ext = 'jpg';
				break;
			}
			case 'jpeg':
			{
				$ext = 'jpg';
				break;
			}
			case 'png':
			{
				$ext = 'png';
				break;
			}
			default:
				return false;
		}

		$fileDB = new AquariumFile();
		$fileDB->userID = Auth::id();
		$fileDB->aquariumID = $log->aquariumID;
		$fileDB->fileType = $ext;

		$fileTitle = Input::get('fileTitle');
		$fileCaption = Input::get('fileCaption');
		if($fileTitle != '')
			$fileDB->title = Input::get('fileTitle');
		if($fileCaption != '')
			$fileDB->caption = Input::get('fileCaption');

		$fileDB->save();
		$fileID = $fileDB->fileID;

		Image::make($file)
			->resize(null, AquariumFile::$thumbHeight, function ($constraint) {
		    $constraint->aspectRatio();
			})
			->save("$path/$fileID-thumb.$ext");

		Image::make($file)
			->resize(null, AquariumFile::$fullHeight, function ($constraint) {
			    $constraint->aspectRatio();
			    $constraint->upsize();
			})
			->save("$path/$fileID-full.$ext");

		$aquariumLogFile = new AquariumLogFile();
		$aquariumLogFile->fileID = $fileID;
		$aquariumLogFile->aquariumLogID = $log->aquariumLogID;
		$aquariumLogFile->save();

		return $fileID;
	}

	private function updateWaterTestLog($aquariumLogID)
	{
		if(Input::get('temperature') != '' ||
		   Input::get('ammonia') != '' ||
		   Input::get('nitrites') != '' ||
		   Input::get('nitrates') != '' ||
		   Input::get('phosphates') != '' ||
		   Input::get('pH') != '' ||
		   Input::get('KH') != '' ||
		   Input::get('GH') != '' ||
		   Input::get('TDS') != '' ||
		   Input::get('amountExchanged') != '')
		{
			$waterTestLog = WaterTestLog::FirstOrNew(array('aquariumLogID' => $aquariumLogID));
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
			if(Input::get('GH') != '')
				$waterTestLog->GH = Input::get('GH');
			if(Input::get('TDS') != '')
				$waterTestLog->TDS = Input::get('TDS');
			if(Input::get('amountExchanged') != '')
				$waterTestLog->amountExchanged = Input::get('amountExchanged');
			$waterTestLog->save();
		}
	}

	// We had to use DB::table here over Eloquent because Eloquent does not
	// support composite primary keys
	private function updateWaterAdditive($aquariumLogID)
	{
		// First see if an additive was selected
		if(Input::get('waterAdditive') != '')
 			if(Input::get('waterAdditive') != 0)
			{
				// See if it exists in the DB
				$additiveLog = DB::table('WaterAdditiveLogs')
					->where('aquariumLogID', '=', $aquariumLogID)
					->where('waterAdditiveID', '=', Input::get('waterAdditive'))
					->first();
				if(isset($additiveLog))
				{
					// Now see if we need to update the additive, or delete the entry
					if(Input::get('waterAdditiveAmount') > 0)
					{
						// There is probably a better way to do this, but convert the standard
						// DB object above into an array and include the amount
						$additiveLog = array('aquariumLogID' => $additiveLog->aquariumLogID,
							'waterAdditiveID' => $additiveLog->waterAdditiveID,
							'amount' => Input::get('waterAdditiveAmount'));
						DB::table('WaterAdditiveLogs')
							->where('aquariumLogID', '=', $aquariumLogID)
							->where('waterAdditiveID', '=', Input::get('waterAdditive'))
							->update($additiveLog);
					}
					elseif(Input::get('waterAdditiveAmount') == 0)
					{
						DB::table('WaterAdditiveLogs')
							->where('aquariumLogID', '=', $aquariumLogID)
							->where('waterAdditiveID', '=', Input::get('waterAdditive'))
							->delete();
					}
				}
				// Otherwise, let's add a new entry
				elseif(Input::get('waterAdditiveAmount') > 0)
				{
					DB::table('WaterAdditiveLogs')->insert(
					    array('aquariumLogID' => $aquariumLogID,
							  'waterAdditiveID' => Input::get('waterAdditive'),
							  'amount' => Input::get('waterAdditiveAmount')));
				}
			}
	}

	// We had to use DB::table here over Eloquent because Eloquent does not
	// support composite primary keys
	private function updateEquipmentLog($aquariumLogID)
	{
		// First see if an equipment was selected
		if(Input::get('equipment') != '')
 			if(Input::get('equipment') != 0)
			{
				// Now check whether it was a maintenance
				if(Input::get('equipmentMaintenance'))
					$maintenance = 'Yes';
				else
					$maintenance = 'No';

				// See if it exists in the DB
				$equipmentLog = DB::table('EquipmentLogs')
					->where('aquariumLogID', '=', $aquariumLogID)
					->where('equipmentID', '=', Input::get('equipment'))
					->first();
				// If so, we update the entry
				if(isset($equipmentLog))
				{
					// As with the WaterAdditiveLogs, this is not elegant and should be improved.
					$equipmentLog = array('aquariumLogID' => $equipmentLog->aquariumLogID,
						'maintenance' => $maintenance);
					DB::table('EquipmentLogs')
						->where('aquariumLogID', '=', $aquariumLogID)
						->where('equipmentID', '=', Input::get('equipment'))
						->update($equipmentLog);
				}
				// Otherwise, we add a new entry
				else
				{
					DB::table('EquipmentLogs')->insert(
					    array('aquariumLogID' => $aquariumLogID,
							  'equipmentID' => Input::get('equipment'),
							  'maintenance' => $maintenance));
				}
			}
	}

	private function updateFoodLog($aquariumLogID)
	{
		//First delete what's there
		DB::table('FoodLogs')->where('aquariumLogID', '=', $aquariumLogID)->delete();
		//Then check to see if food is part of input
		if(Input::get('food') != '')
 			if(Input::get('food') != 0)
				foreach(Input::get('food') as $foodID)
					DB::table('FoodLogs')->insert(array('aquariumLogID' => $aquariumLogID, 'foodID' => $foodID));
	}

	private function updateAquariumLifeLog($aquariumLogID)
	{
		//First delete what's there
		DB::table('AquariumLifeLogs')->where('aquariumLogID', '=', $aquariumLogID)->delete();
		//Then check to see if food is part of input
		if(Input::get('aquariumLife') != '')
 			if(Input::get('aquariumLife') != 0)
				foreach(Input::get('aquariumLife') as $aquariumLifeID)
					DB::table('AquariumLifeLogs')
						->insert(array('aquariumLogID' => $aquariumLogID,
									   'aquariumLifeID' => $aquariumLifeID));
	}

	private function generateLogSummary($aquariumLogID)
	{
		// Water Test Log
		$summary = '';
		$waterLog = WaterTestLog::where('aquariumLogID', '=', $aquariumLogID)->first();
		if(isset($waterLog))
		{
			if($waterLog->temperature != '' ||
			   $waterLog->ammonia != '' ||
			   $waterLog->nitrites != '' ||
			   $waterLog->nitrates != '' ||
			   $waterLog->phosphates != '' ||
			   $waterLog->pH != '' ||
			   $waterLog->KH != '' ||
   			   $waterLog->GH != '' ||
   			   $waterLog->TDS != '')
				$summary = 'Tested Water';
			if($waterLog->amountExchanged > 0)
			{
				if($summary != '')
					$summary .= ', ';
				$summary .= 'Exchanged Water';
			}
		}

		// Water Additives Log
		$waterAdditiveLog = WaterAdditiveLog::where('aquariumLogID', '=', $aquariumLogID)
			->join('WaterAdditives', 'WaterAdditives.waterAdditiveID', '=', 'WaterAdditiveLogs.waterAdditiveID')
			->get();
		if(count($waterAdditiveLog) > 0)
		{
			foreach ($waterAdditiveLog as $additive)
			{
				if($summary != '')
					$summary .= ', ';
				$summary .= 'Added '.$additive->name;
			}
		}

		// Equipment Log
		$equipmentLog = EquipmentLog::where('aquariumLogID', '=', $aquariumLogID)
			->join('Equipment', 'Equipment.equipmentID', '=', 'EquipmentLogs.equipmentID')
			->get();
		if(count($equipmentLog) > 0)
		{
			foreach ($equipmentLog as $equipment)
			{
				if($summary != '')
					$summary .= ', ';
				$summary .= 'Maintained '.$equipment->name;
			}
		}

		// Food
		$foodLog = FoodLog::where('aquariumLogID', '=', $aquariumLogID)
			->join('Food', 'Food.foodID', '=', 'FoodLogs.foodID')
			->get();
		$foodLogCount = count($foodLog);
		if($foodLogCount > 0)
		{
			if($summary != '')
				$summary .= ', ';
			$summary .= 'Fed Fish';

			$count = 0;
			foreach($foodLog as $foodItem)
			{
				$summary .= ' '.$foodLog[$count]->name;
				if($count + 1 < $foodLogCount)
					$summary .= ' &amp; ';
				++$count;
			}
		}

		// Aquarium Life
		$aquariumLifeLog = AquariumLifeLog::where('aquariumLogID', '=', $aquariumLogID)
			->join('AquariumLife', 'AquariumLife.aquariumLifeID',
				'=', 'AquariumLifeLogs.aquariumLifeID')
			->get();
		$aquariumLifeLogCount = count($aquariumLifeLog);
		if($aquariumLifeLogCount > 0)
		{
			if($summary != '')
				$summary .= ', ';
			$summary .= 'Looked At';

			$count = 0;
			foreach($aquariumLifeLog as $life)
			{
				if($life->nickname)
					$name = $life->nickname;
				else
					$name = $life->life->commonName;

				$summary .= ' '.$name;
				if($count + 1 < $aquariumLifeLogCount)
					$summary .= ' &amp; ';
				++$count;
			}
		}

		if($summary != '')
			return $summary;
		return null;
	}

	private static function validateLog()
	{
		$validator = Validator::make(
			Input::all(),
			array('logDate' => 'date',
				'temperature' => 'numeric|min:0|max:100',
				'ammonia' => 'numeric|min:0|max:999',
				'nitrites' => 'numeric|min:0|max:999',
				'nitrates' => 'numeric|min:0|max:999',
				'phosphates' => 'numeric|min:0|max:999',
				'pH' => 'numeric|min:0|max:99',
				'KH' => 'numeric|min:0|max:255',
				'GH' => 'numeric|min:0|max:255',
				'TDS' => 'numeric|min:0|max:65535',
				'amountExchanged' => 'numeric|min:1|max:65535',
				'waterAdditiveAmount' => 'numeric|min:1|max:999',
				'name' => 'max:48',
				'fileTitle' => 'max:48')
				//'file' => 'size:5242880|mimes:image/png,image/jpeg')

		);

		return $validator;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($aquariumID)
	{
		$logs = AquariumLog::where('aquariumID', '=', $aquariumID)
			->orderBy('logDate', 'desc')
			->paginate(20);
		return View::make('aquariumlogs/logs')
			->with('aquariumID', $aquariumID)
			->with('logs', $logs);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($aquariumID)
	{
		DB::beginTransaction();

		$food = Food::where('Food.userID', '=', Auth::id())
                        ->orWhere('Food.userID', '=', null)
			->get();

		$aquariumLife = AquariumLife::where('aquariumID', '=', $aquariumID)
			->get();

		//$waterAdditives = WaterAdditive::get();

		$waterAdditives = array('0' => 'None') + WaterAdditive::lists('name', 'waterAdditiveID');

		$equipment = array('0' => 'None') + Equipment::where('aquariumID', '=', $aquariumID)
			->lists('name', 'equipmentID');

		$measurementUnits = Aquarium::where('aquariumID', '=', $aquariumID)
			->select('measurementUnits')
			->first();

		DB::commit();

		return View::make('aquariumlogs/editlog')
			->with('aquariumID', $aquariumID)
			->with('food', $food)
			->with('aquariumLife', $aquariumLife)
			->with('waterAdditives', $waterAdditives)
			->with('equipment', $equipment)
			->with('measurementUnits', $measurementUnits);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($aquariumID)
	{
		$validator = self::validateLog();
		if ($validator->fails())
			return Redirect::to("aquariums/$aquariumID/logs/create")
				->withInput(Input::all())
				->withErrors($validator);

		$log = new AquariumLog();
		$log->aquariumID = $aquariumID;
		$log->comments = Input::get('comments');

		$logDate = Input::get('logDate');
		if(isset($logDate))
			if($logDate != '')
				$log->logDate = strtotime(Input::get('logDate'));

		DB::beginTransaction();
		$log->save();
		$aquariumLogID = $log->aquariumLogID;
		$this->updateWaterTestLog($aquariumLogID);
		$this->updateWaterAdditive($aquariumLogID);
		$this->updateEquipmentLog($aquariumLogID);
		$this->updateFoodLog($aquariumLogID);
		$this->updateAquariumLifeLog($aquariumLogID);

		if (Input::hasFile('file'))
			if(!$this->uploadFile($log))
			{
				DB::rollback();
				return Redirect::to("aquariums/$aquariumID/logs/create")
					->withInput(Input::except('file'))
					->withErrors(array('message' => "Could not upload file"));
			}

		$log->summary = $this->generateLogSummary($aquariumLogID);
		$log->save();

		DB::commit();

		return Redirect::to("aquariums/$aquariumID/logs/$aquariumLogID/edit");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($aquariumID, $logID)
	{
		DB::beginTransaction();
		$log = AquariumLog::where('AquariumLogs.aquariumLogID', '=', $logID)
			->leftjoin('WaterTestLogs', 'WaterTestLogs.aquariumLogID', '=', 'AquariumLogs.aquariumLogID')
			->select('AquariumLogs.aquariumLogID', 'AquariumLogs.aquariumID', 'logDate',
				'summary', 'comments', 'temperature', 'ammonia', 'nitrites', 'nitrates',
				'phosphates', 'pH', 'KH', 'GH', 'TDS', 'amountExchanged')
			->first();
		$food = Food::leftjoin('FoodLogs', function ($join) use($logID)
			{
				$join->on('FoodLogs.foodID', '=', 'Food.foodID')
					->where('FoodLogs.aquariumLogID', '=', $logID);
			})
			->selectraw('Food.foodID AS foodID, name, IF(aquariumLogID, true, false) AS selected')
			->get();

		$waterAdditives = array('0' => 'None') + WaterAdditive::lists('name', 'waterAdditiveID');
		$equipment = array('0' => 'None') + Equipment::where('aquariumID', '=', $aquariumID)
			->lists('name', 'equipmentID');

		$waterAdditiveLogs = WaterAdditiveLog::where('aquariumLogID', '=', $logID)
			->join('WaterAdditives', 'WaterAdditives.waterAdditiveID', '=', 'WaterAdditiveLogs.waterAdditiveID')
			->get();

		$equipmentLogs = EquipmentLog::where('aquariumLogID', '=', $logID)
			->join('Equipment', 'Equipment.equipmentID', '=', 'EquipmentLogs.equipmentID')
			->get();

		$foodLogs = FoodLog::where('aquariumLogID', '=', $logID)
			->join('Food', 'Food.foodID', '=', 'FoodLogs.foodID')
			->get();

		$files = AquariumLogFile::where('aquariumLogID', '=', $logID)
			->join('Files', 'Files.fileID', '=', 'AquariumLogFiles.fileID')
			->get();

		DB::commit();

		return View::make('aquariumlogs/log')
			->with('aquariumID', $aquariumID)
			->with('log', $log)
			->with('food', $food)
			->with('waterAdditives', $waterAdditives)
			->with('waterAdditiveLogs', $waterAdditiveLogs)
			->with('equipmentLogs', $equipmentLogs)
			->with('equipment', $equipment)
			->with('foodLogs', $foodLogs)
			->with('files', $files);
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

		$aquarium = Aquarium::where('aquariumID', '=', $aquariumID)
			->first();

		$log = AquariumLog::where('AquariumLogs.aquariumLogID', '=', $logID)
			->where('AquariumLogs.aquariumID', '=', $aquariumID)
			->leftjoin('WaterTestLogs', 'WaterTestLogs.aquariumLogID', '=', 'AquariumLogs.aquariumLogID')
			->leftjoin('AquariumLogFavorites', 'AquariumLogFavorites.aquariumLogID', '=', 'AquariumLogs.aquariumLogID')
			->select('AquariumLogs.aquariumLogID', 'AquariumLogs.aquariumID', 'logDate',
				'summary', 'comments', 'temperature', 'ammonia', 'nitrites', 'nitrates',
				'phosphates', 'pH', 'KH', 'GH', 'TDS', 'amountExchanged', 'name')
			->first();

		if(!$log)
			return Redirect::to("aquariums/$aquariumID/");

		$food = Food::leftjoin('FoodLogs', function ($join) use($logID)
			{
				$join->on('FoodLogs.foodID', '=', 'Food.foodID')
					->where('FoodLogs.aquariumLogID', '=', $logID);
			})
			->where('Food.userID', '=', Auth::id())
			->orWhere('Food.userID', '=', null)
			->selectraw('Food.foodID AS foodID, name, IF(aquariumLogID, true, false) AS selected')
			->get();

		$aquariumLife = AquariumLife::leftjoin('AquariumLifeLogs', function ($join) use($logID)
			{
				$join->on('AquariumLifeLogs.aquariumLifeID', '=', 'AquariumLife.aquariumLifeID')
					->where('AquariumLifeLogs.aquariumLogID', '=', $logID);
			})
			->join('Life', 'Life.lifeID', '=', 'AquariumLife.lifeID')
			->selectraw('AquariumLife.aquariumLifeID AS aquariumLifeID,
				nickname, commonName,
				IF(aquariumLogID, true, false) AS selected')
			->get();

		$waterAdditives = array('0' => 'None') + WaterAdditive::lists('name', 'waterAdditiveID');
		$equipment = array('0' => 'None') + Equipment::where('aquariumID', '=', $aquariumID)
			->lists('name', 'equipmentID');

		$waterAdditiveLogs = WaterAdditiveLog::where('aquariumLogID', '=', $logID)
			->join('WaterAdditives', 'WaterAdditives.waterAdditiveID', '=', 'WaterAdditiveLogs.waterAdditiveID')
			->get();

		$equipmentLogs = EquipmentLog::where('aquariumLogID', '=', $logID)
			->join('Equipment', 'Equipment.equipmentID', '=', 'EquipmentLogs.equipmentID')
			->get();

		$foodLogs = FoodLog::where('aquariumLogID', '=', $logID)
			->join('Food', 'Food.foodID', '=', 'FoodLogs.foodID')
			->get();

		$aquariumLifeLogs = AquariumLifeLog::where('aquariumLogID', '=', $logID)
			->join('AquariumLife', 'AquariumLife.aquariumLifeID',
				'=', 'AquariumLifeLogs.aquariumLifeID')
			->join('Life', 'Life.lifeID', '=', 'AquariumLife.lifeID')
			->get();

		$files = AquariumLogFile::where('aquariumLogID', '=', $logID)
			->join('Files', 'Files.fileID', '=', 'AquariumLogFiles.fileID')
			->get();

		DB::commit();

		return View::make('aquariumlogs/editlog')
			->with('aquariumID', $aquariumID)
			->with('log', $log)
			->with('food', $food)
			->with('aquariumLife', $aquariumLife)
			->with('waterAdditives', $waterAdditives)
			->with('waterAdditiveLogs', $waterAdditiveLogs)
			->with('equipmentLogs', $equipmentLogs)
			->with('equipment', $equipment)
			->with('foodLogs', $foodLogs)
			->with('aquariumLifeLogs', $aquariumLifeLogs)
			->with('measurementUnits', $aquarium->getMeasurementUnits())
			->with('files', $files);
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
		if(Input::get('delete'))
			return $this->destroy($aquariumID, $aquariumLogID);

		$validator = self::validateLog();
		if ($validator->fails())
			return Redirect::to("aquariums/$aquariumID/logs/$aquariumLogID/edit")
				->withInput(Input::all())
				->withErrors($validator);

		DB::beginTransaction();
		$log = AquariumLog::where('aquariumLogID', '=', $aquariumLogID)->first();

		if($log->aquariumID != $aquariumID)
		{
			DB::rollback();
			return Redirect::to("aquariums");
		}

		$log->comments = Input::get('comments');
		$logDate = Input::get('logDate');
		if(isset($logDate))
			if($logDate != '')
				$log->logDate = strtotime(Input::get('logDate'));
		$this->updateWaterTestLog($aquariumLogID);
		$this->updateWaterAdditive($aquariumLogID);
		$this->updateEquipmentLog($aquariumLogID);
		$this->updateFoodLog($aquariumLogID);
		$this->updateAquariumLifeLog($aquariumLogID);

		if (Input::hasFile('file'))
		{
			if(!$this->uploadFile($log))
			{
				DB::rollback();
				return Redirect::to("aquariums/$aquariumID/logs/create")
					->withInput(Input::except('file'))
					->withErrors(array('message' => "Could not upload file"));
			}
		}

		$log->summary = $this->generateLogSummary($aquariumLogID);

		// Add/Update Log as a favorite if it has been given a name
		$name = Input::get('name');
		if(isset($name))
		{
			if($name != '')
			{
				$favorite = AquariumLogFavorite::where('aquariumLogID', '=', $aquariumLogID)
					->where('aquariumID', '=', $aquariumID)
					->first();
				if(!$favorite)
				{
					$favorite = new AquariumLogFavorite();
					$favorite->aquariumLogID = $aquariumLogID;
					$favorite->aquariumID = $aquariumID;
				}
				$favorite->name = $name;
				$favorite->save();
			}
			else
			{
				$favorite = AquariumLogFavorite::where('aquariumLogID', '=', $aquariumLogID)
					->where('aquariumID', '=', $aquariumID)
					->delete();
			}
		}

		$log->save();
		DB::commit();

		return Redirect::to("aquariums/$aquariumID/logs/$aquariumLogID/edit");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($aquariumID, $aquariumLogID)
	{
		$log = AquariumLog::where('aquariumLogID', '=', $aquariumLogID)
			->where('aquariumID', '=', $aquariumID)
			->first();
		$log->delete();
		return Redirect::to("aquariums/$aquariumID/");
	}

	/* Non Resource Functions */
	public function getFavorites($aquariumID)
	{
		$favorites = AquariumLogFavorite::where('AquariumLogFavorites.aquariumID',
				'=', $aquariumID)
			->join('AquariumLogs',
				'AquariumLogs.aquariumLogID', '=', 'AquariumLogFavorites.aquariumLogID')
			->select('AquariumLogFavorites.aquariumLogID',
					'AquariumLogFavorites.name',
					'AquariumLogs.summary')
			->get();
		return View::make('aquariumlogs/favorites')
			->with('aquariumID', $aquariumID)
			->with('favorites', $favorites);
	}

	public function storeFavorite($aquariumID)
	{
		DB::beginTransaction();
		$aquariumLogID = Input::get('favoriteLog');
		$results = DB::select('CALL ProcessFavoriteLog(?,?)',array($aquariumLogID, $aquariumID));
		$log = AquariumLog::where('aquariumLogID', '=', $results[0]->newAquariumLogID)->first();
		$log->summary = $this->generateLogSummary($aquariumLogID);
		$log->save();
		DB::commit();
		return Redirect::to("aquariums/$aquariumID");
	}

	public function getFeedings($aquariumID)
	{
		$days = Input::get('days');
		if(!isset($days))
			$days = 7;

		$food = DB::select(
			"SELECT Food.name AS name, COUNT(Food.name) AS count
			 FROM AquariumLogs
			 JOIN FoodLogs ON FoodLogs.aquariumLogID = AquariumLogs.aquariumLogID
			 JOIN Food ON Food.foodID = FoodLogs.foodID
			 WHERE AquariumLogs.aquariumID = ?
			 AND logDate >= DATE_SUB(NOW(), INTERVAL ? Day)
			 GROUP BY Food.name", array($aquariumID, $days));

		$logs = AquariumLog::where('aquariumID', '=', $aquariumID)
			->join('FoodLogs', 'FoodLogs.aquariumLogID', '=', 'AquariumLogs.aquariumLogID')
			->groupby('logDate')
			->orderby('logDate', 'desc')
			->paginate(20);
		return View::make('aquariumlogs/feedings')
			->with('aquariumID', $aquariumID)
			->with('days', $days)
			->with('food', $food)
			->with('logs', $logs);

	}

}
