<?php

namespace Tests\Feature\Owner;

use App\Activity;
use App\Dealership;
use App\Enums\ActivityTypes;
use App\Enums\LeadStatus;
use App\Events\LeadAsignado;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LeadsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function an_owner_can_see_the_list_of_leads()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $this->loggedInOwner(null, $dealership);
        $seller1 = factory(User::class)->state('seller')->create(['dealership_id' => $dealership->id]);
        $seller2 = factory(User::class)->state('seller')->create(['dealership_id' => $dealership->id]);
        factory(Lead::class, 5)->create([
            'dealership_id' => $dealership->id,
            'user_id' => $seller1->id,
        ]);
        factory(Lead::class, 5)->create([
            'dealership_id' => $dealership->id,
            'user_id' => $seller2->id,
        ]);
        $response = $this->get('/seller/leads');
        $response->assertStatus(200);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_owner_can_assign_leads()
    {
        // $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->state('owner')->create(['dealership_id' => $dealership->id]);
        $seller1 = factory(User::class)->state('seller')->create([
            'dealership_id' => $owner->dealershipid,
        ]);
        $this->actingAs($owner);
        $lead = factory(Lead::class)->create([
            'dealership_id' => $dealership->id,
            'user_id' => null,
            'status' => LeadStatus::Derivado,
        ]);
        $route = route('owner.leads.update', $lead);
        $data = ['user_id' => $seller1->id];

        $this->assertNull($lead->user_id);
        $this->assertCount(0, $lead->activities);

        $this->put($route, $data);
        $lead = Lead::first();
        $this->assertEquals($lead->user_id, $seller1->id);
        $this->assertTrue($lead->isTaken());
        $this->assertCount(1, $lead->activities);

        $activity = $lead->activities()->first();
        $observation = 'Se asignó a '.$seller1->name.' para manejar este prospecto';
        $this->assertEquals($activity->observation, $observation);
        $this->assertEquals($activity->type, ActivityTypes::Asignar);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_event_is_dispatched_when_a_owner_assigns_a_lead()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->state('owner')->create(['dealership_id' => $dealership->id]);
        $seller1 = factory(User::class)->state('seller')->create([
            'dealership_id' => $owner->dealershipid,
            'is_active' => 1,
        ]);
        $this->actingAs($owner);
        $lead = factory(Lead::class)->create([
            'dealership_id' => $dealership->id,
            'user_id' => null,
            'status' => LeadStatus::Derivado,
        ]);

        Event::fake([LeadAsignado::class]);

        $route = route('owner.leads.update', $lead);
        $data = ['user_id' => $seller1->id];
        $this->put($route, $data);

        Event::assertDispatched(LeadAsignado::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_owner_can_release_leads()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->state('owner')->create(['dealership_id' => $dealership->id]);
        $seller1 = factory(User::class)->state('seller')->create([
            'dealership_id' => $owner->dealershipid,
        ]);
        $this->actingAs($owner);
        $lead = factory(Lead::class)->create([
            'dealership_id' => $dealership->id,
            'user_id' => $seller1->id,
            'status' => LeadStatus::Contactado,
        ]);
        $route = route('owner.leads.update', $lead);
        $data = ['user_id' => null];

        $this->assertEquals($lead->user_id, $seller1->id);
        $this->assertCount(0, $lead->activities);

        $this->put($route, $data);
        $lead = Lead::first();
        $this->assertNull($lead->user_id);
        $this->assertCount(1, $lead->activities);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_owner_can_store_a_lead()
    {
        $this->withOutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->states('owner')->create(['dealership_id' => $dealership->id]);
        $seller = factory(User::class)->states('seller')->create(['dealership_id' => $dealership->id]);
        $this->loggedInOwner($owner);
        $lead = factory(Lead::class)->make();

        $this->assertCount(0, Lead::all());
        $this->assertCount(0, Activity::all());
        $data = [
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'address' => $lead->address,
            'geo_loc_1' => $lead->geo_loc_1,
            'geo_loc_2' => $lead->geo_loc_2,
            'geo_loc_3' => $lead->geo_loc_3,
            'brand' => $lead->brand,
            'user_id' => $seller->id,
        ];

        Event::fake([LeadAsignado::class]);

        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();

        $this->assertCount(1, Lead::all());
        // tres actividades, creacion, derivacion y asignacion
        $this->assertCount(3, Activity::all());
        $lead = Lead::first();
        // se crea asignado
        $this->assertEquals(LeadStatus::Asignado, $lead->status);
        $this->assertEquals($lead->dealership_id, $owner->dealership_id);
        $this->assertEquals($lead->user_id, $seller->id);

        Event::assertDispatched(LeadAsignado::class);
    }
}
