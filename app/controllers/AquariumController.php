<?php

class AquariumController extends BaseController
{

	public function getIndex()
	{
		return self::getAquariums();
	}

	public function getAquariums()
	{
		$aquariums = Aquarium::all();
	    return View::make('aquariums')->with('aquariums', $aquariums);		
	}
	
	public function getAquarium($aquariumID)
	{
		if ($aquariumID == null)
			return Redirect::intended('aquariums');

		$aquarium = Aquarium::find($aquariumID);

		if($aquarium->measurementUnits = 'Metric')
		{
			$volumeUnits = 'L';
			$lengthUnits = 'cm';
		}
		else
		{
			$volumeUnits = 'Gal';
			$lengthUnits = 'inches';
		}

		return View::make('aquarium')
			->with('aquarium', $aquarium)
			->with('volumeUnits', $volumeUnits)
			->with('lengthUnits', $lengthUnits);
		
	}
}
	
?>