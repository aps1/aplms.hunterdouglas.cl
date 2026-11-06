<?php

namespace App\Repositories;

use App\Dealership;
use Illuminate\Support\Facades\Auth;

class BrandsRepository
{
    /**
     * Array con marcas por pais para dealer
     *
     * @return array
     */
    public function getForDealership(Dealership $dealership)
    {
        $brands = [];
        $brands['wcp'] = brand_name('wcp', Auth::user()->localization);

        return $brands;
    }

    /**
     * consigue todos para los admin
     *
     * @return array
     */
    public function all()
    {
        $brands = [];
        $brands['wcp'] = brand_name('wcp', Auth::user()->localization);

        return $brands;
    }
}
