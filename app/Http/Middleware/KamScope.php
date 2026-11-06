<?php

namespace App\Http\Middleware;

use Closure;

class KamScope
{
    /**
     * Agregar el kam id para los global scopes que filtraran
     * la data de los kam segun las sucursales que tengan.
     * No se puede usar auth en los scopes por que tambien
     * se va a scopear el model user y entra en bucle
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->isKam()) {
            session()->put('kam_id', $request->user()->id);
        }

        return $next($request);
    }
}
