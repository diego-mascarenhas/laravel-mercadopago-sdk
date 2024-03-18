<?php

namespace DiegoMascarenhas\MercadoPago\Providers;

use Illuminate\Support\ServiceProvider;
use DiegoMascarenhas\MercadoPago\MP;

class MercadoPagoServiceProvider extends ServiceProvider
{

	protected $mp_app_id;
	protected $mp_app_secret;
	protected $mp_app_ssl;
	protected $mp_app_sandbox;

	public function boot()
	{

		$this->publishes([__DIR__ . '/../config/mercadopago.php' => config_path('mercadopago.php')]);

		$this->mp_app_id = config('mercadopago.app_id');
		$this->mp_app_secret = config('mercadopago.app_secret');
		$this->mp_app_ssl = config('mercadopago.app_ssl');
		$this->mp_app_sandbox = config('mercadopago.app_debug');
	}

	public function register()
	{
		$this->app->singleton('MP', function ()
		{
			return new MP($this->mp_app_id, $this->mp_app_secret, $this->mp_app_ssl, $this->mp_app_sandbox);
		});
	}
}