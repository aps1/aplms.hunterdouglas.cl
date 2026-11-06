<?php

namespace Tests;

use App\Dealership;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Carbon;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    const UNAUTHORIZE_CODE = 404;

    /**
     * Crea un admin y logealo
     *
     * @param [type] $user
     */
    public function loggedInAdmin($user = null)
    {
        $user = $user ?? factory(User::class)->state('admin')->create();
        $this->actingAs($user);
        $this->setLocalization($user->localization);

        return $user;
    }

    /**
     * Crea un kam y logealo
     *
     * @param [type] $user
     * @return void
     */
    public function loggedInKam($user = null)
    {
        $user = $user ?? factory(User::class)->state('kam')->create();
        $this->actingAs($user);
        $this->setLocalization($user->localization);

        return $user;
    }

    /**
     * Crea un admin y logealo
     *
     * @param [type] $user
     * @return void
     */
    public function loggedInSeller($user = null, $dealership = null)
    {
        $user = $user ?? factory(User::class)->state('seller')->create();
        if ($dealership) {
            $user->dealership_id = $dealership->id;
        } else {
            $user->dealership_id = factory(Dealership::class)->create()->id;
        }
        $user->save();
        $this->actingAs($user);
        $this->setLocalization($user->localization);

        return $user;
    }

    /**
     * Crea un admin y logealo
     *
     * @param [type] $user
     * @return void
     */
    public function loggedInOwner($user = null, $dealership = null)
    {
        $user = $user ?? factory(User::class)->state('owner')->create();
        if ($dealership) {
            $user->dealership_id = $dealership->id;
        } else {
            $user->dealership_id = factory(Dealership::class)->create()->id;
        }
        $user->save();
        $this->actingAs($user);
        $this->setLocalization($user->localization);

        return $user;
    }

    /**
     * La fecha por defecto para los selectores
     *
     * @param  int  $previus
     * @return void
     */
    public function getDefaultStart($previus = 1)
    {
        if ($previus > 1) {
            return Carbon::now()->subDays(90 * $previus)->startOfDay();
        } else {
            return Carbon::now()->subDays(90)->startOfDay();
        }
    }

    /**
     * La fecha por defecto para los selectores
     *
     * @param  int  $previus
     * @return void
     */
    public function getDefaultEnd($previus = 1)
    {
        if ($previus > 1) {
            return Carbon::now()->subDays(90)->endOfDay();
        } else {
            return Carbon::now()->endOfDay();
        }
    }

    /**
     * Localizacion para el global scope
     *
     * @param [type] $localization
     * @return void
     */
    public function setLocalization($localization)
    {
        $this->withSession(['localization' => $localization]);
    }
}
