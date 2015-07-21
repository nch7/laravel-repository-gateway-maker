<?php namespace Nch7\LaravelRepositoryGatewayMaker;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use File;
use Config;
use Artisan;
class RepositoryGatewayInit extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'repogate:init';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->pathFromApp = Config::get('laravel-repository-gateway-maker::path');
		$this->path = app_path().'/'.$this->pathFromApp;
	}


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		if ($this->confirm('This will delete '.$this->path.' directory completely. Do you wish to continue? [yes|no]'))
		{
			File::deleteDirectory($this->path);

			mkdir($this->path, 0755);
			mkdir($this->path.'/Gateways', 0755);
			mkdir($this->path.'/Repositories', 0755);
			
			$ServiceProviderTemplate = str_replace(
				'%path%', 
				$this->pathFromApp, 
				file_get_contents(__DIR__.'/../../templates/Repositories/RepositoryServiceProvider.php')
			);

			file_put_contents($this->path.'/Repositories/RepositoryServiceProvider.php', $ServiceProviderTemplate);
			Artisan::call('dump-autoload');
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
		);
	}

}
