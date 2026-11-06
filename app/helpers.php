<?php

if (! function_exists('brand_name')) {
    /**
     * Siempre hunterdouglas para AP
     *
     * @param  string  $brand_code  wcp wcp2
     * @param  string  $country  cl ar
     * @return void
     */
    function brand_name(string $brand_code, string $country)
    {
        return 'HunterDouglas';
    }
}

if (! function_exists('timezone_from_code')) {
    /**
     * Consigue el string del timezone basado en el codigo de pais.
     * Para ser usado por User model en middleware de localizacion y por el
     * lead unified service con la funcion date_default_timezone_set
     *
     * @param  string  $country
     * @return void
     */
    function timezone_by_country($country)
    {
        switch ($country) {
            case 'cl':
                $timezone = 'America/Santiago';
                break;
            case 'ar':
                $timezone = 'America/Argentina/Buenos_Aires';
                break;
            case 'mx':
                $timezone = 'America/Mexico_City';
                break;
            default:
                $timezone = 'America/Santiago';
                break;
        }

        return $timezone;
    }
}
