<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LeadOrigins extends Enum
{
    const HunterDouglasArchitectural = 1;

    const HunterDouglasHome = 6;

    const HunterDouglasLatam = 8;

    const Otros = 3;

    const Manual = 4;

    const Cliengo = 5;

    const Landbot = 7;

    const Luxalon = 9;

    public static function toOriginalSelectArray(): array
    {
    
        return parent::toSelectArray();
    }

    public static function onlyManualOrigins(): array
    {

     
        return parent::toSelectArray();
    }
    public static function getTexto($keyinput): string 
    {
     //  die("ini".$keyinput."end");
        $array =   self::getArray();
        foreach (self::getValues() as $key) {
            $array[$key] = (self::getDescription($key));
        }
        return $array[$keyinput];
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
