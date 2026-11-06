<?php

namespace App\Actions;

use App\Dealership;
use App\Enums\ActivityTypes;
use App\Enums\LeadOrigins;
use App\Enums\ProductCategory;
use App\Events\LeadAsignado;
use App\Events\ManualLeadRegistered;
use App\Exceptions\NotifiableException;
use App\Lead;
use App\Repositories\LeadsRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreManualLead
{
    /**
     * handle the request
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $this->validate($request);
        $dealership_id = $this->getDealershiId($request);
        $seller_id = $this->getSellerId($request);
        $lead = Lead::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => __('phoneCode').$request->get('phone'),
            'country' => $request->user()->localization,
            'localization' => $request->user()->localization,
            'address' => $request->get('address'),
            'geo_loc1' => $request->get('geo_loc1'),
            'geo_loc2' => $request->get('geo_loc2'),
            'geo_loc3' => $request->get('geo_loc3'),
            'brand' => $this->parseBrand($request),
            'origin' => LeadOrigins::Manual,
            'source' => $request->get('source'),
            'product_category' => $request->get('product_category') ?? ProductCategory::Otros,
            'date' => now(),
            'product' => $request->get('product'),
            'dealership_id' => $dealership_id,
            'user_id' => $seller_id,
            'priority' => $request->get('priority'),
            'message' => $request->get('message'),
            'is_subscribed' => 0,
        ]);
        $identifier = $this->formatIdentifier($lead->id, $request->user()->localization);
        $lead->update(['web_identifier' => $identifier]);

        $activity_data_for_create = [
            'type' => ActivityTypes::Creado,
            'user_id' => $request->user()->id,
            'observation' => __('messages.leads.registrado_manual'),
        ];
        $lead->addActivity($activity_data_for_create);

        event(new ManualLeadRegistered($lead));

        // Si hay dealership, derivalo
        if ($dealership_id) {
            $this->deriveLead($lead, $dealership_id);
        }
        if ($seller_id) {
            $this->assignLead($lead, $request);
        }

        return $lead;
    }

    /**
     * @return void
     */
    protected function validate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            throw new NotifiableException($validator->errors()->first());
        }
    }

    /**
     * Dealership para la creacion manual del lead
     *
     * @return void
     */
    protected function getDealershiId(Request $request)
    {
        // si es el vendedor o el owner tiene dealer_id
        if ($request->user()->isAdmin()) {
            return $request->get('dealership_id');
        }

        return $request->user()->dealership_id;
    }

    /**
     * User para la creacion manual del lead
     *
     * @return void
     */
    protected function getSellerId(Request $request)
    {
        // si es el vendedor o el owner, tienen dealer_id
        if ($request->user()->isSeller()) {
            return $request->user()->id;
        }

        return $request->get('user_id');
    }

    /**
     * El nombre de la marca

     *
     * @return string
     */
    protected function parseBrand(Request $request)
    {
        if (! $request->get('brand')) {
            return null;
        }

        return brand_name($request->get('brand'), $request->user()->localization);
    }

    /**
     * Formatea el correlativo
     *
     * @return string
     */
    protected function formatIdentifier(int $id, string $country_code)
    {
        $country_code = strtoupper($country_code);

        return $country_code.'M'.sprintf('%08d', $id);
    }

    /**
     * Deriva el lead, con correos si es desde el admin
     *
     * @return void
     */
    protected function deriveLead(Lead $lead, $dealership_id)
    {
        $dealership = Dealership::findOrFail($dealership_id);
        $leadsRepo = new LeadsRepository;
        $leadsRepo->deriveToDealershipFromManualForm($lead, $dealership);
    }

    /**
     * Asigna el lead, con correos si es desde el admin
     *
     * @return void
     */
    protected function assignLead(Lead $lead, Request $request)
    {
        $seller = User::find($lead->user_id);
        $lead->addActivity([
            'user_id' => $request->user()->id,
            'type' => ActivityTypes::Asignar,
            'observation' => __('messages.leads.asignado', ['vendedor' => $seller->name]),
        ]);
        event(new LeadAsignado($lead));
    }
}
