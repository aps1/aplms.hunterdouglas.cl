<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ProductCategory extends Enum
{
    const Interiores = 1;

    const Solar = 2;

    const Fachadas = 3;

    const Cubiertas = 4;

    const Otros = 5;

    /**
     * @param [type] $value
     */
    public static function getDescription($value): string
    {
        if ($value === self::Interiores) {
            return 'Productos Interiores';
        }
        if ($value === self::Solar) {
            return 'Control Solar';
        }
        if ($value === self::Cubiertas) {
            return 'Cubiertas-Tejuelas';
        }

        return parent::getDescription($value);
    }

    public static function toOriginalSelectArray(): array
    {
        return parent::toSelectArray();
    }
}
