<?php

namespace Tests\Feature\Seller;

use App\Activity;
use App\Dealership;
use App\Enums\ActivityTypes;
use App\Enums\LeadOrigins;
use App\Enums\LeadStatus;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * leads que esta trabajando el vendedor
     *
     * @return void
     *
     * @test
     */
    public function a_seller_has_pending_leads()
    {
        $seller = factory(User::class)->state('seller')->create();
        $taken_leads = factory(Lead::class, 5)->create([
            'user_id' => $seller->id,
            'status' => LeadStatus::Contactado,
        ]);
        $another_seller = factory(User::class)->state('seller')->create();
        $another_taken_leads = factory(Lead::class, 5)->create([
            'user_id' => $another_seller->id,
            'status' => LeadStatus::Contactado,
        ]);
        $losed_leads = factory(Lead::class, 5)->state('lost')->create([
            'user_id' => $seller->id,
            'status' => LeadStatus::Perdido,
        ]);
        $this->assertCount(5, $seller->pending_leads);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_has_closed_leads()
    {
        $seller = factory(User::class)->state('seller')->create();
        $closed_leads = factory(Lead::class, 5)->state('lost')->create([
            'user_id' => $seller->id,
        ]);
        $another_seller = factory(User::class)->state('seller')->create();
        $another_win_leads = factory(Lead::class, 5)->state('won')->create([
            'user_id' => $another_seller->id,
        ]);
        $wined_leads = factory(Lead::class, 5)->state('won')->create([
            'user_id' => $seller->id,
        ]);
        $losed_leads = factory(Lead::class, 5)->create([
            'user_id' => $seller->id,
            'status' => LeadStatus::Contactado,
        ]);
        $another_losed_leads = factory(Lead::class, 5)->state('lost')->create([
            'user_id' => $another_seller->id,
        ]);
        $this->assertCount(10, $seller->closed_leads);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_see_leads_from_his_dealership()
    {
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership->id,
        ]);
        $open_leads = factory(Lead::class, 5)->state('open')->create([
            'dealership_id' => $dealership->id,
        ]);
        $another_dealership = factory(Dealership::class)->create();
        factory(Lead::class, 5)->state('open')->create([
            'dealership_id' => $another_dealership->id,
        ]);
        $this->assertCount(5, $seller->open_leads);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_see_the_list_of_leads()
    {
        $this->withoutExceptionHandling();
        $this->loggedInSeller();
        $response = $this->get('/seller/leads');
        $response->assertStatus(200);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_see_a_lead()
    {
        $this->withoutExceptionHandling();
        $seller = $this->loggedInSeller();
        $lead = factory(Lead::class)->create([
            'dealership_id' => $seller->dealership_id,
            'origin' => LeadOrigins::Manual,
        ]);
        $response = $this->get(route('seller.leads.show', $lead));
        $response->assertStatus(200);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_protects_the_show_route()
    {
        // $this->withoutExceptionHandling();
        $dealership1 = factory(Dealership::class)->create();
        $dealership2 = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create(['dealership_id' => $dealership1->id]);
        $another_seller = factory(User::class)->state('seller')->create(['dealership_id' => $dealership2->id]);
        $lead = factory(Lead::class)->create([
            'dealership_id' => $dealership2->id,
            'user_id' => $another_seller->id,
        ]);
        $this->actingAs($seller);
        $response = $this->call('GET', route('seller.leads.show', $lead));
        $response->assertForbidden();
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_take_a_lead_by_adding_tomar_activity()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership->id,
        ]);
        $lead = factory(Lead::class)->state('open')->create([
            'dealership_id' => $dealership->id,
            'status' => LeadStatus::Derivado,
        ]);
        $route = route('seller.leads.take', $lead);
        $this->actingAs($seller)->get($route)->assertRedirect();

        $lead = Lead::find($lead->id);
        $this->assertCount(1, Activity::all());
        $this->assertTrue($lead->isTaken());
        $this->assertEquals($lead->status, LeadStatus::Asignado);
        $this->assertCount(1, $seller->pending_leads);

        $activity = $lead->activities->first();
        $this->assertEquals($activity->type, ActivityTypes::Tomar);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_add_a_cotizar_activity()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership->id,
        ]);
        $lead = factory(Lead::class)->state('open')->create([
            'dealership_id' => $dealership->id,
            'status' => LeadStatus::Contactado,
            'user_id' => $seller->id,
        ]);
        $data = [
            'type' => ActivityTypes::Cotizar,
            'observation' => 'Esta es la opbservacion',
            'net_value' => 45552,
        ];
        $route = route('leads.activities.store', $lead);
        $this->actingAs($seller)->post($route, $data);

        $lead = Lead::find($lead->id);

        $this->assertCount(1, Activity::all());
        $this->assertTrue($lead->isTaken());
        $this->assertEquals($lead->status, LeadStatus::Cotizado);
        $this->assertCount(1, $seller->pending_leads);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_lose_a_lead_by_adding_it_a_desiste_activity()
    {
        $this->withoutExceptionHandling();
        $seller = factory(User::class)->state('seller')->create();
        $lead = factory(Lead::class)->create([
            'status' => LeadStatus::Contactado,
            'dealership_id' => $seller->dealership_id,
            'user_id' => $seller->id,
        ]);
        $data = [
            'type' => ActivityTypes::Desiste,
            'observation' => 'Esta es la opbservacion',
        ];
        $route = route('leads.activities.store', $lead);
        $this->actingAs($seller)->post($route, $data);

        $lead = Lead::find($lead->id);
        $this->assertCount(1, Activity::all());
        $this->assertTrue($lead->isLost());
        $this->assertCount(1, $seller->closed_leads);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_won_a_lead()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create(['dealership_id' => $dealership->id]);
        $lead = factory(Lead::class)->create([
            'dealership_id' => $seller->dealership->id,
        ]);
        $data = [
            'type' => ActivityTypes::Venta,
            'observation' => 'Esta es la opbservacion',
        ];
        $route = route('leads.activities.store', $lead);
        $this->actingAs($seller)->post($route, $data);

        $lead = Lead::find($lead->id);

        $this->assertCount(1, Activity::all());
        $this->assertTrue($lead->isWon());
        $this->assertCount(1, $seller->won_leads);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_store_a_lead()
    {
        $this->withOutExceptionHandling();
        $seller = $this->loggedInSeller();
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
        ];
        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();

        $this->assertCount(1, Lead::all());
        // tres actividades, creacion, derivacion y asignacion
        $this->assertCount(3, Activity::all());
        $lead = Lead::first();
        // se crea asignado
        $this->assertEquals(LeadStatus::Asignado, $lead->status);
        $this->assertEquals($lead->dealership_id, $seller->dealership_id);
        $this->assertEquals($lead->user_id, $seller->id);
    }
}
