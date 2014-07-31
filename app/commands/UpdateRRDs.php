<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateRRDs extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'spark:updateRRDs';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates aquarium RRDs';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $path = Config::get('spark.rrdPath');

        $aquariums = Aquarium::whereNotNull('sparkID')
            ->whereNotNull('sparkToken')
            ->select('aquariumID', 'sparkID', 'sparkToken')
            ->get();

		foreach ($aquariums as $aquarium)
    	{
       	 	$this->info("Updating RRD for Aquarium ".$aquarium->aquariumID);

			$updater = new RRDUpdater(Config::get('spark.rrdPath').$aquarium->aquariumID.".rrd");
			$url = Config::get('spark.url').
				$aquarium->sparkID.'/stats?access_token='.
				$aquarium->sparkToken;
		
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$curlResponse = curl_exec($curl);
			if ($curlResponse === false)
			{
			    curl_close($curl);
				$this->error('Erorr occured while using curl exec');
			}
			curl_close($curl);
			$decoded = json_decode($curlResponse);
			if (isset($decoded->response->status) && $decoded->response->status == 'ERROR')
				$this->error('Error occured with response');

			$stats = explode(':', $decoded->result);
			$updater->update(array(
				'temperature' => $stats[0],
				'heater' => $stats[1],
				'light' => $stats[2]
			));		
		} 
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		/*
		return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
		*/
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		/*
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
		*/
		return array();
		
	}

}
