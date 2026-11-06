<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Auth;

final class LeadSources extends Enum
{
    const Facebook = 1;

    const FacebookDA = 14;

    const Instagram = 2;

    const InstagramDA = 15;

    const WhatsAppDA = 16;

    const BrandsiteHD = 3;

    const BrandsiteFL = 4;

    const Telefono = 5;

    const TelefonoDA = 17;

    const SEM = 11;

    const Display = 13;

    const Visita = 7;

    const Showroom = 10;

    const Email = 8;

    const EmailDA = 18;

    const Web = 9;

    const Otros = 12;

    const OtrosDA = 19;
    
    public static function getArray(): array // Cambié el nombre a getArray
    {
        $array = [];
        foreach (self::getkeys() as $key) {
            $array[$key] = (self::getValue($key));
        }
        return $array;
    }

    /**
     * @param [type] $value
     */
    public static function getDescription($value): string
    {
        if ($value === self::Facebook) {
            return 'Facebook / Redes Sociales';
        }
        if ($value === self::FacebookDA) {
            return 'D.A. Facebook / Redes Sociales';
        }
        if ($value === self::Instagram) {
            return 'Instagram / Redes Sociales';
        }
        if ($value === self::InstagramDA) {
            return 'D.A. Instagram / Redes Sociales';
        }
        if ($value === self::BrandsiteHD) {
            return 'Chat o WhatsApp / Brand Site HD';
        }
        if ($value === self::BrandsiteFL) {
            return 'Chat o WhatsApp / Brand Site 2da';
        }
        if ($value === self::WhatsAppDA) {
            return 'D.A. WhatsApp';
        }
        if ($value === self::Telefono) {
            return 'Llamada Tel.';
        }
        if ($value === self::TelefonoDA) {
            return 'D.A. Llamada Tel.';
        }
        if ($value === self::SEM) {
            return 'D.A. Llamada Tel. SEM';
        }
        if ($value === self::Display) {
            return 'D.A. Llamada Tel. Display';
        }
        if ($value === self::Visita) {
            return 'D.A. Visita Tienda';
        }
        if ($value === self::Showroom) {
            return 'Visita Showroom';
        }
        if ($value === self::EmailDA) {
            return 'D.A. Email';
        }
        if ($value === self::OtrosDA) {
            return 'D.A. Otros';
        }

        return parent::getDescription($value);
    }

    /**
     * Retorna solo las origines manuales segun HD o Dis
     */
    public static function toSelectArray(): array
    {
        $array =  self::getArray();
        $selectArray = [];

        $sourcesForDA = [
            self::FacebookDA,
            self::InstagramDA,
            self::WhatsAppDA,
            self::TelefonoDA,
            self::SEM,
            self::Display,
            self::Visita,
            self::EmailDA,
            self::OtrosDA,
        ];

        $sourcesForAdmin = [
            self::Facebook,
            self::Instagram,
            self::BrandsiteHD,
            self::BrandsiteFL,
            self::Telefono,
            self::Showroom,
            self::Email,
            self::Otros,
        ];

        foreach ($array as $key => $value) {
            $selectArray[$value] = self::getDescription($value);
        }

        unset($selectArray[self::Web]);

        // Los usuarios HD ven algo distinto a los distribuidores
        if (Auth::user()->isAdminLevel()) {
            foreach ($sourcesForDA as $source_for_da) {
                unset($selectArray[$source_for_da]);
            }
        } else {
            foreach ($sourcesForAdmin as $source_for_admin) {
                unset($selectArray[$source_for_admin]);
            }
        }

        return $selectArray;
    }

  
    public static function toOriginalSelectArray(): array
    {
        return parent::toSelectArray();
    }

    
}
