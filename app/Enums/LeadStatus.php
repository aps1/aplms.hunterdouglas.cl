<?php
namespace App\Enums;

use App\User;
use BenSampo\Enum\Enum;

final class LeadStatus extends Enum {
    const Creado = 1;

    const Derivado = 2;

    const Asignado = 3;

    const Contactado = 4;

    const Visitado = 13;

    const Cotizado = 5;

    const Perdido = 6;

    const Vendido = 7;

    //   const Foliado = 11;
    // const Clausurado = 8;
    const Descartado = 9;
    // const NoRequiereAccion = 10;
    //   const Rederivar = 12;

    /**
     * override getDescription
     *
     * @param [type] $value
     */
    public static function getDescription($value): string {
        /*  if ($value === self::NoRequiereAccion) {
              return 'No requiere acción';
          }*/
        /*
        if ($value === self::Foliado) {
            return 'Vendido con Folios';
        }*/
        /* if ($value === self::Rederivar) {
             return 'Creado';
         }
         */
        if ($value === self::Perdido) {
            return 'No vendido';
        }
   //return parent::getDescription($value);
 //    return self::getValue($value);
        return self::getTexto($value);
    }

    /**
     * Retorna solo las actividades para tomar una quotation
     */
    public static function toSelectArrayForUserType(User $user): array {

        $array =  self::getArray();
        $selectArray = [];

        foreach ($array as $key => $value) {
            $selectArray[$value] = self::getDescription($value);
        }
        if ($user->isKam()) {
            // unset($selectArray[static::Clausurado]);
            //   unset($selectArray[static::NoRequiereAccion]);
        }
        if ($user->isDealerPersonal()) {
            //   unset($selectArray[static::Rederivar]);
            //  unset($selectArray[static::Clausurado]);
            //  unset($selectArray[static::NoRequiereAccion]);
        }

        return $selectArray;
    }

    public static function toOriginalSelectArray(): array {
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


    public static function getTexto($keyinput): string 
    {
     //  die("ini".$keyinput."end");
        $array =   self::getArray();
        foreach (self::getValues() as $key) {
            $array[$key] = (self::getkey($key));
        }
        return $array[$keyinput];
    }


}
