<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProcessEmailReminders extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'aquaricle:process-email-reminders';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Process E-mail Reminders';

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
		$withinDays = 2;
		$subject = 'Aquaricle: Maintenance Reminder';
		$body = '';
		
		$this->info('Processing Maintenance Reminders');
		
		// Get Active Users
		$users = User::active()
			->select(DB::raw('userID, username, email'))
			->get();
		
		// Loop through active users
		foreach ($users as $user)
		{
			$body = '';
			$this->info("Processing User ".$user->userID."\n");
			// Check on Water Changes
			$aquariums = DB::select('CALL WaterChangesDue(?,?)',array($user->userID,$withinDays));
			foreach ($aquariums as $aquarium)
			{
				if($aquarium->dueIn > 0)
					$body .= $aquarium->name." aquarium is due for a water change in ".$aquariums->dueIn." days or less!\n";
				elseif($aquarium->dueIn == 0)
					$body .= $aquarium->name." aquarium is due for a water change today!\n";
				else
					$body .= $aquarium->name." aquarium is OVERDUE for a water change!\n";
				$body .= "Last water change was performend on ".$aquarium->logDate."\n\n";
			}

			// Check on Equipment Maintenance			
			$equipment = DB::select('CALL EquipmentMaintenanceDue(?, ?)', array($user->userID, $withinDays));
			foreach ($equipment as $equip)
			{
				if($equip->dueIn > 0)
					$body .= "Your ".$equip->name." 
						is due for maintenance on your ".
						$equip->aquariumname.
						"aquarium in ".
						$equip->dueIn.
						" days or less!\n";
				elseif($equip->dueIn == 0)
					$body .= "Your ".$equip->name." 
						is due for maintenance on your ".
						$equip->aquariumname.
						"aquarium today!\n";
				else
					$body .= "Your ".$equip->name." 
						is OVERDUE for maintenance on your ".
						$equip->aquariumname.
						"aquarium!\n";
				$body .= "Last maintenance was performend on ".$equip->logDate."\n\n";
			}

			if($body != '')
			{
				Mail::send(array('text' => 'email.reminders'), array('body' => $body), function($message) use ($user, $subject)
				{
				    $message->to($user->email, $user->username)->subject($subject);
				});
			}
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
