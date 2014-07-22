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
			->where('emailReminders', '=', 'Yes')
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
					$body .= "Your ".$aquarium->name." is due for a water change in ".$aquarium->dueIn." days or less!\n";
				elseif($aquarium->dueIn == 0)
					$body .= "Your ".$aquarium->name." is due for a water change today!\n";
				else
				$body .= "Last water change was performend on ".$aquarium->logDate."\n\n";
			}
			// Check on Equipment Maintenance			
			$equipment = DB::select('CALL EquipmentMaintenanceDue(?, ?)', array($user->userID, $withinDays));

			foreach ($equipment as $equip)
			{
				if($equip->dueIn > 0)
					$body .= "Your ".$equip->equipmentName.
						" on your ".$equip->aquariumName.
						" is due for maintenance in ".
						$equip->dueIn.
						" days or less!\n";
				elseif($equip->dueIn == 0)
					$body .= "Your ".$equip->equipmentName.
						" on your ".$equip->aquariumName.
						" is due for maintenance today!\n";
				else
					$body .= "Your ".$equip->equipmentName.
						" on your ".$equip->aquariumName.
						" is OVERDUE for maintenance!\n";
				$body .= "Last maintenance was performend on ".$equip->logDate."\n\n";
			}

			if($body != '')
			{
				Mail::send(array('text' => 'email.reminders'), 
				array('body' => $body, 'username' => $user->username), 
				function($message) use ($user, $subject)
				{
				    $message->to($user->email, $user->username)->subject($subject);
				});
			}
		}
	}
}
