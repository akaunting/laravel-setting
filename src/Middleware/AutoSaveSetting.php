<?php

namespace Akaunting\Setting\Middleware;

use Akaunting\Setting\Contracts\Driver;
use Closure;

class AutoSaveSetting
{
	/**
	 * Create a new save settings middleware
	 * 
	 * @param Driver $settings
	 */
	public function __construct(Driver $setting)
	{
		$this->setting = $setting;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);

		$this->setting->save();
		
		return $response;
	}
}