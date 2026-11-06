<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Para los modelos con localizacion
 */
trait Localizable
{
    /**
     * @return void
     */
    protected static function bootLocalizable()
    {
        static::creating(function ($model) {
            if (session('localization')) {
                $model->localization = session('localization');
            }
        });
        static::addGlobalScope('localization', function (Builder $builder) {
            if (session('localization')) {
                $builder->where('localization', '=', session('localization'));
            }
        });
    }
}
