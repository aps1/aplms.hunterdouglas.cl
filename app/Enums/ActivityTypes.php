<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ActivityTypes extends Enum
{
    const Creado = 0;

    const Comentario = 1;

    const Derivar = 2;

    const Contacto = 3;

    const Cotizar = 4;

    const Venta = 5;

    const Desiste = 6;

    const Rechazar = 8;

    const Asignar = 9;

    const Tomar = 10;

    const Clausurar = 11;

    const Descartar = 12;

    const Ignorar = 13;

    const Foliar = 14;

    const Visitar = 15;

    /**
     * override getDescription
     *
     * @param [type] $value
     */
    public static function getDescription($value): string
    {
        if ($value === self::Venta) {
            return 'Registrar Venta';
        }

        if ($value === self::Desiste) {
            return 'No vendido';
        }

        if ($value === self::Ignorar) {
            return 'No requiere acción';
        }

        if ($value === self::Foliar) {
            return 'Agregar Folios';
        }

        return parent::getDescription($value);
    }

    public static function toOriginalSelectArray(): array
    {
        return parent::toSelectArray();
    }
}
