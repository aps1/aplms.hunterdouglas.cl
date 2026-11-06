<?php

namespace App\Scopes;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class KamScope implements Scope
{
    /**
     * El scope global para el Kam via sus sucursales.
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (session('kam_id')) {
            // Scope para dealer
            if (get_class($model) == \App\Dealership::class) {
                $this->dealerScope($builder);
            }
            // Scope para user
            if (get_class($model) == \App\User::class) {
                $this->userScope($builder);
            }
            // Scope para leads
            if (get_class($model) == \App\Lead::class) {
                $this->leadsScope($builder);
            }
            // Scope para leads
            if (get_class($model) == \App\Contact::class) {
                $this->contactScope($builder);
            }
        }
    }

    /**
     * Dealerships que tengan branches con kam id logeado
     *
     * @return void
     */
    protected function dealerScope(Builder $builder)
    {
        $builder->where('kam_id', session('kam_id'));
    }

    /**
     * Usuarios de los dealersites que puede ver el
     * kam.
     *
     * @return void
     */
    protected function userScope(Builder $builder)
    {
        $dealer_ids = $this->getDealershipsId();

        $users_ids = DB::table('users')
            ->whereIn('dealership_id', $dealer_ids)
            ->select('users.id')
            ->get()
            ->pluck('id')
            ->toArray();

        // agrega su usuario
        $users_ids[] = session('kam_id');
        $builder->whereIn('id', $users_ids);
    }

    /**
     * leads de los dealersites que puede ver el
     * kam, junto con los que estan sin asignar.
     *
     * @return void
     */
    protected function leadsScope(Builder $builder)
    {
        $dealer_ids = $this->getDealershipsId();
        $leads_ids = DB::table('leads')
            ->whereIn('dealership_id', $dealer_ids)
            ->select('leads.id')
            ->get()
            ->pluck('id')
            ->toArray();
        // Puede ver los de sus distribuidores o los que no tienen deaerlship_id
        // que no son clausurados o que no requieren accion
        $builder->whereIn('id', $leads_ids)->orWhere(function ($query) {
            $query->whereNull('dealership_id');
            // ->whereNotIn('status', [LeadStatus::Clausurado, LeadStatus::NoRequiereAccion]);
        });
    }

    /**
     * Contacts de los dealersites que puede ver el kam
     *
     * @return void
     */
    protected function contactScope(Builder $builder)
    {
        $dealer_ids = $this->getDealershipsId();
        $contacts_ids = DB::table('contacts')
            ->whereIn('dealership_id', $dealer_ids)
            ->select('contacts.id')
            ->get()
            ->pluck('id')
            ->toArray();
        $builder->whereIn('id', $contacts_ids);
    }

    /**
     * Consigue los id de los dealerships que el kam
     *
     * @return array
     */
    protected function getDealershipsId()
    {
        return DB::table('dealerships')
            ->where('kam_id', session('kam_id'))
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();
    }
}
