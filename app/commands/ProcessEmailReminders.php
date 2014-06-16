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
			$this->info("Processing User ".$user->userID."\n");
			$aquariums = DB::select('CALL WaterChangesDue(?,?)',array($user->userID,2));
			foreach ($aquariums as $aquarium)
			{
				$body = $aquarium->name." is due for a water change in 2 days!\n".
					"Last Water Change was on ".$aquarium->logDate."\n\n";
			}
			if($body != '')
			{
				Mail::send('email.reminders', array('body' => $body), function($message) use ($user, $subject)
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
