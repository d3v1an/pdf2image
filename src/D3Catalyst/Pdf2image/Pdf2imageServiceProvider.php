<?php namespace D3Catalyst\Pdf2image;

use Illuminate\Support\ServiceProvider;

class Pdf2imageServiceProvider extends ServiceProvider {

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
		$this->package('d3-catalyst/pdf2image');

		// Custom command
		$this->app->bind('utils:pdf2image', function($app) {
		    return new Pdf2image();
		});
		$this->commands(array(
		    'utils:pdf2image'
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
