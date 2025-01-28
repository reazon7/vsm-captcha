<?php

namespace REAZON\VerySimpleMathCaptcha;

use Illuminate\Support\ServiceProvider;
use REAZON\VerySimpleMathCaptcha\VsmCaptcha;

class VsmCaptchaServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Publish configuration files
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('vsm-captcha.php'),
		], 'vsm-captcha');

		// HTTP routing
		if (!config('captcha.disable')) {
			$router = $this->app['router'];

			$router->get('vsm-captcha/{code?}', '\REAZON\VerySimpleMathCaptcha\VsmCaptchaController@showCaptcha')->middleware('web');
		}

		// Generate captcha

		// Validator
		$validator = $this->app['validator'];

		// Validator extensions
		$validator->extend('vsm_captcha', function ($attribute, $value, $parameters) {
			return config('vsm-captcha.disable') || (!empty($value) && vsm_captcha_check($value));
		});
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'vsm-captcha');

		// Binding
		$this->app->bind('vsm-captcha', function ($app) {
			return new VsmCaptcha();
		});
	}
}
