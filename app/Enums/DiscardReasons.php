<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Auth;

final class DiscardReasons extends Enum
{
    const Spam = 1;

    const Inventario = 2;

    const Localizacion = 3;

    const Repetido = 4;

    const Informacion = 5;

    const Empleo = 6;

    const Compro = 8;

    const Otro = 7;

    /**
     * @param [type] $value
     */
    public static function getDescription($value): string
    {

        if ($value === self::Inventario) {
            return 'Otros productos';
        }
        if ($value === self::Localizacion) {
            return 'Distancia geográfica';
        }
        if ($value === self::Repetido) {
            return 'Prospecto repetido';
        }
        if ($value === self::Informacion) {
            return 'Información técnica';
        }
        if ($value === self::Empleo) {
            return 'Solicitud de empleo';
        }
        if ($value === self::Compro) {
            return 'Cliente ya compró';
        }
        if ($value === self::Otro) {
            return 'Otros motivos';
        }

        return parent::getDescription($value);
    }

    /**
     * Retorna solo las origines manuales segun HD o Dis
     */
    public static function toSelectArray(): array
    {
        $array = self::getArray();
        $selectArray = [];

        foreach ($array as $key => $value) {
            $selectArray[$value] = self::getDescription($value);
        }
        // Los usuarios distribuidores no ven solicitud de distribuicion ni de empleo
        if (! Auth::user()->isAdminLevel()) {
            unset($selectArray[self::Empleo]);
            unset($selectArray[self::Compro]);
        }

        return $selectArray;
    }

    public static function toOriginalSelectArray(): array
    {
        return parent::toSelectArray();
    }

    public static function getArray(): array // Cambié el nombre a getArray
    {
        $array = [];
        foreach (self::getkeys() as $key) {
            $array[$key] = (self::getValue($key));
        }
        return $array;
    }
}
