<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Support\Carbon;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $country_code = $request->user()->localization;
        // Setea la localizacion para las traducciones
        App::setLocale($country_code);
        // setea el timezone dependiendo del usuario logeado, no funciona con al config de laravel
        date_default_timezone_set(timezone_by_country($country_code));
        Carbon::setLocale('es');

        return $next($request);
    }
}
