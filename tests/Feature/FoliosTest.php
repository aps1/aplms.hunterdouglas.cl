<?php

namespace Tests\Feature;

use App\Dealership;
use App\Enums\ActivityTypes;
use App\Enums\LeadStatus;
use App\Folio;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ModelHelpers;
use Tests\TestCase;

class FoliosTest extends TestCase
{
    use ModelHelpers, RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function it_belongs_to_a_lead()
    {
        $this->assertBelongsTo('lead', Lead::class, Folio::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_user_can_associte_folios_to_lead_in_foliar_activity()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership->id,
        ]);
        $this->setLocalization('ar');
        $this->actingAs($seller);
        $lead = factory(Lead::class)->create(['status' => LeadStatus::Vendido]);
        $data = [
            'type' => ActivityTypes::Foliar,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
            'folios' => ['R67786', 'Y779898'],
        ];
        $store_route = route('leads.activities.store', $lead);
        $response = $this->post($store_route, $data);
        $response->assertRedirect();
        $update_lead = Lead::find($lead->id);
        $this->assertCount(2, $update_lead->folios);
        $this->assertCount(1, $update_lead->activities);
        $this->assertEquals($update_lead->activities->first()->type, ActivityTypes::Foliar);
        $this->assertEquals('R67786', $update_lead->folios()->first()->number);
        $this->assertEquals('ar', $update_lead->folios()->first()->localization);
        $this->assertEquals(LeadStatus::Foliado, $update_lead->status);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_user_can_updates_folios()
    {
        $this->withoutExceptionHandling();
        $lead = factory(Lead::class)->create();
        factory(Folio::class, 2)->create([
            'lead_id' => $lead->id,
        ]);
        $this->assertCount(2, $lead->folios);
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership->id,
        ]);
        $this->actingAs($seller);
        $data = [
            'type' => ActivityTypes::Foliar,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
            'folios' => ['Y767676'],
        ];
        $store_route = route('leads.activities.store', $lead);
        $response = $this->post($store_route, $data);
        $response->assertRedirect();

        $update_lead = Lead::where('email', $lead->email)->first();
        $this->assertCount(1, $update_lead->folios);
        $this->assertEquals('Y767676', $update_lead->folios->first()->number);
        $this->assertEquals(LeadStatus::Foliado, $update_lead->status);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_user_can_delete_folios()
    {
        $this->withoutExceptionHandling();
        $lead = factory(Lead::class)->create(['status' => LeadStatus::Foliado]);
        factory(Folio::class, 2)->create([
            'lead_id' => $lead->id,
        ]);
        $this->assertCount(2, $lead->folios);
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership->id,
        ]);
        $this->actingAs($seller);
        $data = [
            'type' => ActivityTypes::Foliar,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
            'folios' => [],
        ];
        $store_route = route('leads.activities.store', $lead);
        $response = $this->post($store_route, $data);
        $response->assertRedirect();

        $update_lead = Lead::where('email', $lead->email)->first();
        $this->assertCount(0, $update_lead->folios);
        $this->assertEquals(LeadStatus::Vendido, $update_lead->status);
    }
}
