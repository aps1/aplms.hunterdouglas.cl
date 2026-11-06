<?php

namespace App\Traits;

use App\Scopes\KamScope;

/**
 * Para los modelos con scope de kam
 */
trait KamScopable
{
    /**
     * @return void
     */
    protected static function bootKamScopable()
    {
        static::addGlobalScope(new KamScope);
    }
}
