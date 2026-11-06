<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserTypes extends Enum
{
    const Admin = 1;

    const Owner = 2;

    const Seller = 3;

    const Manager = 4;

    const Kam = 5;

    /**
     * @param [type] $value
     */
    public static function getDescription($value): string
    {
        if ($value === self::Admin) {
            return 'Administrador';
        }
        if ($value === self::Owner) {
            return 'Dueño(a)';
        }
        if ($value === self::Seller) {
            return 'Vendedor(a)';
        }
        if ($value === self::Manager) {
            return 'Encargado(a)';
        }

        return parent::getDescription($value);
    }

    public static function toOriginalSelectArray(): array
    {
        return parent::toSelectArray();
    }
}
