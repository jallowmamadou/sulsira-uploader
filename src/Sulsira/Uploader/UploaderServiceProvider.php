<?php namespace Sulsira\Uploader;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class UploaderServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

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

	public function boot(){

		$this->package('sulsira/uploader');
        AliasLoader::getInstance()->alias('Uploader', 'Sulsira\Uploader\Uploader');
	}

}
