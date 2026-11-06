<?php

namespace App\Repositories;

use App\Contact;
use App\Dealership;
use App\Enums\ContactTypes;
use App\Exceptions\NotifiableException;
use App\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactsRepository
{
    /**
     * Asocia el lead al contacto creado o conseguido
     *
     * @return void
     */
    public function asociateContactFromLead(Lead $lead, Dealership $dealership)
    {
        $contact = $this->firstOrCreateFromLead($lead, $dealership);
        $contact->leads()->save($lead);
    }

    /**
     * Consigue el contacto por email y dealer id si existe, si no crealo
     */
    public function firstOrCreateFromLead(Lead $lead, Dealership $dealership): Contact
    {
        $created_contact = Contact::where([
            ['email', '=', $lead->email],
            ['dealership_id', '=', $dealership->id],
        ])->first();
        if ($created_contact) {
            return $created_contact;
        }

        return Contact::create([
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'contact_method' => $lead->contact_method,
            'address' => $lead->address,
            'geo_loc1' => $lead->geo_loc1,
            'geo_loc2' => $lead->geo_loc2,
            'geo_loc3' => $lead->geo_loc3,
            'dealership_id' => $dealership->id,
            'type' => ContactTypes::Prospecto,
            'source' => $lead->source,
            'localization' => $lead->localization,
            'is_subscribed' => $lead->is_subscribed,
            'created_at' => $lead->created_at,
        ]);
    }

    /**
     * @return void
     */
    public function storeFromRequest(Request $request)
    {
        $data = $request->all();
        if ($request->user()->dealership_id) {
            $data['dealership_id'] = $request->user()->dealership_id;
        }
        $this->validatesStore($data);
        $data['localization'] = $request->user()->localization;
        $data['phone'] = $data['phone'] ? __('phoneCode').$data['phone'] : null;
        $data['phone2'] = $data['phone2'] ? __('phoneCode').$data['phone2'] : null;
        $data['is_subscribed'] = $data['is_subscribed'] ?? 0;

        return Contact::create($data);
    }

    protected function validatesStore(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required',
            'dealership_id' => 'required',
        ]);
        if ($validator->fails()) {
            throw new NotifiableException('El email y el distribuidor son requeridos');
        }
    }

    /**
     * @return void
     */
    public function updateFromRequest(Contact $contact, Request $request)
    {
        $this->validatesUpdate($contact, $request);

        return $contact->update($request->all());
    }

    /**
     * Valida el email
     *
     * @return void
     */
    public function validatesUpdate(Contact $contact, Request $request)
    {
        if (! $request->has('email')) {
            throw new NotifiableException('El email es requerido');
        }
        $email_exists = Contact::where('email', $request->email)
            ->where('dealership_id', $contact->dealership_id)
            ->where('id', '!=', $contact->id)
            ->count();
        if ($email_exists) {
            throw new NotifiableException('Ya existe un contacto con ese email');
        }
    }
}
