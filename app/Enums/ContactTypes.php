<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ContactTypes extends Enum
{
    const Prospecto = 1;

    const Cliente = 2;

    /**
     * @param [type] $value
     */
    public static function getDescription($value): string
    {
        if ($value === self::Prospecto) {
            return 'Cliente/Potencial';
        }
        if ($value === self::Cliente) {
            return 'Cliente/Vendido';
        }

        return parent::getDescription($value);
    }

    public static function toOriginalSelectArray(): array
    {
        return parent::toSelectArray();
    }
}
