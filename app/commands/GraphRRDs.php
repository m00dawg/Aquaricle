<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GraphRRDs extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'spark:graphRRDs';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create graphs of RRDs';

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
			if(!is_dir(Config::get('spark.graphPath').$aquarium->aquariumID))
				mkdir(Config::get('spark.graphPath').$aquarium->aquariumID);
			
       	 	$this->info("Create RRD Graphs for Aquarium ".$aquarium->aquariumID);

			$tempDailySmall = new  RRDGraph(Config::get('spark.graphPath').
				$aquarium->aquariumID."/temp-daily-small.png");
			$tempDailySmall->setOptions(
				array(
					"--title" => "Temperature",
					"--width" => Config::get('spark.smallWidth'),
					"--height" => Config::get('spark.smallHeight'),
					"--slope-mode",
					"DEF:temperature=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:temperature:AVERAGE",
					"VDEF:lastTemperature=temperature,LAST",
					"VDEF:minTemperature=temperature,MINIMUM",
					"VDEF:maxTemperature=temperature,MAXIMUM",
					"VDEF:avgTemperature=temperature,AVERAGE",
					'LINE1:temperature#FF0000:Temperature',
					"GPRINT:lastTemperature:Current\: %2.2lfC",
					"GPRINT:minTemperature:Min\: %2.2lfC",
					"GPRINT:maxTemperature:Max\: %2.2lfC",
					"GPRINT:avgTemperature:Average\: %2.2lfC\l",
				)
			);
			$tempDailySmall->save();
			
			$tempDailyLarge = new  RRDGraph(Config::get('spark.graphPath').
				$aquarium->aquariumID."/temp-daily-large.png");
			$tempDailyLarge->setOptions(
				array(
					"--title" => "Temperature",
					"--width" => Config::get('spark.largeWidth'),
					"--height" => Config::get('spark.largeHeight'),
					"--slope-mode",
					"DEF:temperature=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:temperature:AVERAGE",
					"VDEF:lastTemperature=temperature,LAST",
					"VDEF:minTemperature=temperature,MINIMUM",
					"VDEF:maxTemperature=temperature,MAXIMUM",
					"VDEF:avgTemperature=temperature,AVERAGE",
					'LINE1:temperature#FF0000:Temperature',
					"GPRINT:lastTemperature:Current\: %2.2lfC",
					"GPRINT:minTemperature:Min\: %2.2lfC",
					"GPRINT:maxTemperature:Max\: %2.2lfC",
					"GPRINT:avgTemperature:Average\: %2.2lfC\l",
				)
			);
			$tempDailyLarge->save();
			
			$tempWeeklySmall = new  RRDGraph(Config::get('spark.graphPath').
				$aquarium->aquariumID."/temp-weekly-small.png");
			$tempWeeklySmall->setOptions(
				array(
					"--title" => "Temperature",
					"--width" => Config::get('spark.smallWidth'),
					"--height" => Config::get('spark.smallHeight'),
					"--slope-mode",
					"--start", "end-1w",
					"DEF:temperature=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:temperature:AVERAGE",
					"VDEF:lastTemperature=temperature,LAST",
					"VDEF:minTemperature=temperature,MINIMUM",
					"VDEF:maxTemperature=temperature,MAXIMUM",
					"VDEF:avgTemperature=temperature,AVERAGE",
					'LINE1:temperature#FF0000:Temperature',
					"GPRINT:lastTemperature:Current\: %2.2lfC",
					"GPRINT:minTemperature:Min\: %2.2lfC",
					"GPRINT:maxTemperature:Max\: %2.2lfC",
					"GPRINT:avgTemperature:Average\: %2.2lfC\l",
				)
			);
			$tempWeeklySmall->save();
			
			$tempWeeklyLarge = new  RRDGraph(Config::get('spark.graphPath').
				$aquarium->aquariumID."/temp-weekly-large.png");
			$tempWeeklyLarge->setOptions(
				array(
					"--title" => "Temperature",
					"--width" => Config::get('spark.largeWidth'),
					"--height" => Config::get('spark.largeHeight'),
					"--slope-mode",
					"--start", "end-1w",
					"DEF:temperature=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:temperature:AVERAGE",
					"VDEF:lastTemperature=temperature,LAST",
					"VDEF:minTemperature=temperature,MINIMUM",
					"VDEF:maxTemperature=temperature,MAXIMUM",
					"VDEF:avgTemperature=temperature,AVERAGE",
					'LINE1:temperature#FF0000:"Temperature"',
					"GPRINT:lastTemperature:Current\: %2.2lfC",
					"GPRINT:minTemperature:Min\: %2.2lfC",
					"GPRINT:maxTemperature:Max\: %2.2lfC",
					"GPRINT:avgTemperature:Average\: %2.2lfC\l",
				)
			);
			$tempWeeklyLarge->save();		
			
			$relaysDailySmall = new  RRDGraph(Config::get('spark.graphPath').
				$aquarium->aquariumID."/relays-daily-small.png");
			$relaysDailySmall->setOptions(
				array(
					"--title" => "Relays",
					"--width" => Config::get('spark.smallWidth'),
					"--height" => Config::get('spark.smallHeight'),
					"--slope-mode",
					"DEF:heater=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:heater:AVERAGE",
					"DEF:light=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:light:AVERAGE",
					"AREA:light#AAAAFF:Light:STACK",
					"AREA:heater#FFFF00:Heater\l:STACK"
				)
			);
			$relaysDailySmall->save();
			
			$relaysDailyLarge = new  RRDGraph(Config::get('spark.graphPath').
				$aquarium->aquariumID."/relays-daily-large.png");
			$relaysDailyLarge->setOptions(
				array(
					"--title" => "Relays",
					"--width" => Config::get('spark.largeWidth'),
					"--height" => Config::get('spark.largeHeight'),
					"--slope-mode",
					"DEF:heater=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:heater:AVERAGE",
					"DEF:light=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:light:AVERAGE",
					"AREA:light#AAAAFF:Light:STACK",
					"AREA:heater#FFFF00:Heater\l:STACK"
				)
			);
			$relaysDailyLarge->save();
			
			$relaysWeeklySmall = new  RRDGraph(Config::get('spark.graphPath').
				$aquarium->aquariumID."/relays-weekly-small.png");
			$relaysWeeklySmall->setOptions(
				array(
					"--title" => "Relays",
					"--width" => Config::get('spark.smallWidth'),
					"--height" => Config::get('spark.smallHeight'),
					"--slope-mode",
					"--start", "end-1w",
					"DEF:heater=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:heater:AVERAGE",
					"DEF:light=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:light:AVERAGE",
					"AREA:light#AAAAFF:Light:STACK",
					"AREA:heater#FFFF00:Heater\l:STACK"
				)
			);
			$relaysWeeklySmall->save();
			
			$relaysWeeklyLarge = new  RRDGraph(Config::get('spark.graphPath').
				$aquarium->aquariumID."/relays-weekly-large.png");
			$relaysWeeklyLarge->setOptions(
				array(
					"--title" => "Relays",
					"--width" => Config::get('spark.largeWidth'),
					"--height" => Config::get('spark.largeHeight'),
					"--slope-mode",
					"--start", "end-1w",
					"DEF:heater=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:heater:AVERAGE",
					"DEF:light=".Config::get('spark.rrdPath').$aquarium->aquariumID.
						".rrd:light:AVERAGE",
					"AREA:light#AAAAFF:Light:STACK",
					"AREA:heater#FFFF00:Heater\l:STACK"
				)
			);
			$relaysWeeklyLarge->save();
			
		}
	}
	
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
