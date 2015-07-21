<?php namespace Nch7\LaravelRepositoryGatewayMaker;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config;
use ErrorException;
use Artisan;

class RepositoryGatewayMaker extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'repogate:make';

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

	public function  checkStructure() {
		if(is_dir($this->path)) {
			if(is_dir($this->path.'/Gateways')) {
				if(is_dir($this->path.'/Repositories')) {
					return true;
				}
			}
		}

		throw new ErrorException('Folders structure is not set, run php artisan repogate:init to create the necessary folders');
		return false;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->checkStructure();
		$model = ucfirst($this->argument('model'));

		$GatewayTemplate = file_get_contents(__DIR__.'/../../templates/Gateways/ModelGateway.php');
		$GatewayTemplate = str_replace('%path%', $this->pathFromApp, $GatewayTemplate);
		$GatewayTemplate = str_replace('%model%', $model, $GatewayTemplate);

		$ModelRepositoryDbTemplate = file_get_contents(__DIR__.'/../../templates/Repositories/ModelRepository/ModelRepositoryDb.php');
		$ModelRepositoryDbTemplate = str_replace('%path%', $this->pathFromApp, $ModelRepositoryDbTemplate);
		$ModelRepositoryDbTemplate = str_replace('%model%', $model, $ModelRepositoryDbTemplate);

		$ModelRepositoryInterfaceTemplate = file_get_contents(__DIR__.'/../../templates/Repositories/ModelRepository/ModelRepositoryInterface.php');
		$ModelRepositoryInterfaceTemplate = str_replace('%path%', $this->pathFromApp, $ModelRepositoryInterfaceTemplate);
		$ModelRepositoryInterfaceTemplate = str_replace('%model%', $model, $ModelRepositoryInterfaceTemplate);

		file_put_contents($this->path.'/Gateways/'.$model.'Gateway.php', $GatewayTemplate);
		
		mkdir($this->path.'/Repositories/'.$model.'Repository', 0755);
		
		file_put_contents($this->path.'/Repositories/'.$model.'Repository/'.$model.'RepositoryDb.php', $ModelRepositoryDbTemplate);
		file_put_contents($this->path.'/Repositories/'.$model.'Repository/'.$model.'RepositoryInterface.php', $ModelRepositoryInterfaceTemplate);
		
		$ServiceProviderTemplate = file_get_contents($this->path.'/Repositories/RepositoryServiceProvider.php');
		$ServiceProviderTemplate = str_replace(
            'public function register() {'
			, 
            'public function register() {
            	$this->app->bind("'.$this->pathFromApp.'\Repositories\\'.$model.'Repository\\'.$model.'RepositoryInterface", "'.$this->pathFromApp.'\Repositories\\'.$model.'Repository\\'.$model.'RepositoryDb");'
			, $ServiceProviderTemplate);

		file_put_contents($this->path.'/Repositories/RepositoryServiceProvider.php', $ServiceProviderTemplate);
	
		Artisan::call('dump-autoload');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('model', InputArgument::REQUIRED, 'Model for which you are creating repository and gateway'),
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
