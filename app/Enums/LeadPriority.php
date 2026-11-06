<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LeadPriority extends Enum
{
    const Baja = 1;

    const Media = 2;

    const Alta = 3;

    const Strategic = 4;

    /**
     * @param [type] $value
     */
    public static function getDescription($value): string
    {
        if ($value === self::Strategic) {
            return 'Estratégico';
        }

        return parent::getDescription($value);
    }

    public static function toOriginalSelectArray(): array
    {
        return parent::toSelectArray();
    }
}
