<?php namespace Nch7\LaravelRepositoryGatewayMaker;

use Illuminate\Support\ServiceProvider;

class LaravelRepositoryGatewayMakerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('nch7/laravel-repository-gateway-maker');
		
		$this->app['config']->package('nch7/laravel-repository-gateway-make', __DIR__.'/../../config');
		
		$this->app->bind('nch7::repogate:make', function($app) {
		    return new RepositoryGatewayMaker();
		});
		
		$this->app->bind('nch7::repogate:init', function($app) {
		    return new RepositoryGatewayInit();
		});

		$this->commands(array(
		    'nch7::repogate:make',
		    'nch7::repogate:init'
		));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
