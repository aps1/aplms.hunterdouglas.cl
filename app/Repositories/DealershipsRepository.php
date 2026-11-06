<?php

namespace App\Repositories;

use App\Dealership;
use App\Exceptions\NotifiableException;
use Illuminate\Support\Facades\Validator;

class DealershipsRepository
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function create(array $data)
    {
        $data['wcp1'] = array_key_exists('wcp1', $data);
        $data['wcp2'] = array_key_exists('wcp2', $data);

        $this->validateCreate($data);
        $data['identifier'] = $this->findIdentifier($data);
        $dealership = Dealership::create($data);
        if (! $dealership) {
            throw new NotifiableException('No pudimos crear al distribuidor');
        }

        return $dealership;
    }

    /**
     * Updatde the dealer
     *
     * @return void
     */
    public function update(Dealership $dealership, array $data)
    {
        $data['wcp1'] = array_key_exists('wcp1', $data);
        $data['wcp2'] = array_key_exists('wcp2', $data);

        $this->validateUpdate($data);
        $update = $dealership->update($data);
        if (! $update) {
            throw new NotifiableException('No pudimos crear al distribuidor');
        }

        return $update;

    }

    /**
     * @return void
     */
    public function validateCreate(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            throw new NotifiableException($validator->errors()->first());
        }

        return true;
    }

    /**
     * Valida update
     *
     * @return void
     */
    public function validateUpdate(array $data)
    {
        $validator = Validator::make($data, Dealership::$rules);
        if ($validator->fails()) {
            throw new NotifiableException($validator->errors()->first());
        }

        return true;
    }

    /**
     * un identificador unico
     *
     * @param [type] $data
     * @return void
     */
    protected function findIdentifier($data)
    {
        if ($data['identifier']) {
            return $data['identifier'];
        }
        $next = Dealership::withoutGlobalScopes()->count() + 1;
        $identifier = 'T'.$next;

        return $identifier;
    }
}
