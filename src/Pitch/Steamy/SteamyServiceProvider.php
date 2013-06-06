<?php namespace Pitch\Steamy;

use Illuminate\Support\ServiceProvider;

class SteamyServiceProvider extends ServiceProvider {

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
		$this->package('pitch/steamy');
		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['steam'] = $this->app->share(function($app)
	    {
	        return new Steam($app['view'], $app['config'], $app['db']);
	    });

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('steam');
	}

}