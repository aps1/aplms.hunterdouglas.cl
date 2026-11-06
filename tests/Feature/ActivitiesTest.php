<?php

namespace Tests\Feature;

use App\Activity;
use App\Dealership;
use App\Enums\ActivityTypes;
use App\Enums\DiscardReasons;
use App\Enums\LeadStatus;
use App\Exceptions\NotifiableException;
use App\Lead;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\ModelHelpers;
use Tests\TestCase;

class ActivitiesTest extends TestCase
{
    use ModelHelpers, RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function it_belogns_to_a_user()
    {
        $this->assertBelongsTo('user', User::class, Activity::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_belogns_to_a_lead()
    {
        $this->assertBelongsTo('lead', Lead::class, Activity::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_belogns_to_a_dealership()
    {
        $this->assertBelongsTo('dealership', Dealership::class, Activity::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_user_can_stores_comments()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create(['dealership_id' => $dealership->id]);
        $this->actingAs($seller);
        $lead = factory(Lead::class)->create();
        $data = [
            'type' => ActivityTypes::Comentario,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
        ];

        $this->assertCount(0, Activity::all());
        $this->assertCount(0, $lead->activities);

        $store_route = route('leads.activities.store', $lead);
        $response = $this->post($store_route, $data);
        $response->assertRedirect();
        $this->assertCount(1, Activity::all());
        $lead = Lead::find($lead->id);
        $this->assertCount(1, $lead->activities);
        $activity = $lead->activities->first();
        $this->assertEquals($dealership->id, $activity->dealership_id);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_user_can_discard_leads()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->create([
            'status' => LeadStatus::Creado,
            'status_reason' => null,
        ]);
        $data = [
            'type' => ActivityTypes::Descartar,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
            'reason' => DiscardReasons::Spam,
        ];

        $this->assertCount(0, Activity::all());
        $this->assertCount(0, $lead->activities);

        $route = route('leads.discard', $lead);
        $response = $this->put($route, $data);
        $response->assertRedirect();
        $this->assertCount(1, Activity::all());
        $lead = Lead::find($lead->id);
        $this->assertCount(1, $lead->activities);
        $this->assertTrue($lead->isDiscarded());
        $this->assertEquals($lead->status_description, 'Descartado');
        $this->assertEquals(DiscardReasons::Spam, $lead->status_reason);
        $this->assertEquals($lead->status_reason_description, 'Spam');

        $activity = $lead->activities->first();
        $this->assertEquals('Descartar', $activity->type_description);
        $this->assertEquals('Spam', $activity->reason_description);
        $this->assertEquals(DiscardReasons::Spam, $activity->reason);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_user_can_reopen_leads_perdidos()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $seller1 = factory(User::class)->state('seller')->create();
        $seller2 = factory(User::class)->state('seller')->create();
        $lead = factory(Lead::class)->create([
            'status' => LeadStatus::Perdido,
            'status_reason' => null,
            'user_id' => $seller1->id,
        ]);
        $data = [
            'type' => ActivityTypes::Contacto,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
        ];

        $this->assertCount(0, Activity::all());
        $this->assertCount(0, $lead->activities);

        $route = route('leads.activities.store', $lead);
        $response = $this->post($route, $data);
        $response->assertRedirect();
        $this->assertCount(1, Activity::all());
        $lead = Lead::find($lead->id);
        $this->assertCount(1, $lead->activities);
        $this->assertTrue($lead->status == LeadStatus::Contactado);
        $this->assertEquals($lead->status_description, 'Contactado');

        $activity = $lead->activities->first();
        $this->assertEquals('Contacto', $activity->type_description);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_lead_queda_en_estado_rederivar_cuando_se_descarta_por_distancia_o_por_otros_motivos()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $this->loggedInOwner(null, $dealership);
        $lead = factory(Lead::class)->create([
            'status' => LeadStatus::Creado,
            'status_reason' => null,
            'dealership_id' => $dealership->id,
        ]);
        $reasons_to_rederivar = [DiscardReasons::Localizacion, DiscardReasons::Otro];
        $data = [
            'type' => ActivityTypes::Descartar,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
            'reason' => $reasons_to_rederivar[array_rand($reasons_to_rederivar)],
        ];

        $this->assertCount(0, Activity::all());
        $this->assertCount(0, $lead->activities);

        $route = route('leads.discard', $lead);
        $response = $this->put($route, $data);
        $response->assertRedirect();
        $this->assertCount(1, Activity::all());
        $lead = Lead::find($lead->id);
        $this->assertCount(1, $lead->activities);
        $this->assertTrue($lead->isRederivable());
        $this->assertNull($lead->dealership_id);
        $this->assertNull($lead->user_id);
        $this->assertEquals($lead->status_description, 'Por rederivar');

        $activity = $lead->activities->first();
        $this->assertEquals('Descartar', $activity->type_description);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_clausurar_a_lead()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->create(['status' => LeadStatus::Creado]);
        $data = [
            'observation' => 'una observacion cualquiera',
        ];

        $this->assertCount(0, Activity::all());
        $this->assertCount(0, $lead->activities);

        $close_route = route('admin.leads.close', $lead);
        $response = $this->put($close_route, $data);
        $response->assertRedirect();
        $this->assertCount(1, Activity::all());
        $lead = Lead::find($lead->id);
        $this->assertCount(1, $lead->activities);

        $activity = Activity::first();
        $this->assertEquals($activity->type, ActivityTypes::Clausurar);
        $this->assertEquals($lead->status, LeadStatus::Clausurado);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_previuos_activity_date()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->create(['created_at' => Carbon::create(2019, 04, 13, 14, 30)]);
        $data = [
            'type' => ActivityTypes::Comentario,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
        ];

        $this->assertCount(0, Activity::all());
        $this->assertCount(0, $lead->activities);

        $store_route = route('leads.activities.store', $lead);
        $this->post($store_route, $data);
        $activity = Activity::first();
        $this->assertEquals($activity->previous_at->toDateTimeString(), '2019-04-13 14:30:00');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_adds_net_value_to_lead_on_cotizado()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create(['dealership_id' => $dealership->id]);
        $this->actingAs($seller);
        $lead = factory(Lead::class)->create();
        $data = [
            'type' => ActivityTypes::Cotizar,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
            'net_value' => '85000',
        ];
        $store_route = route('leads.activities.store', $lead);
        $response = $this->post($store_route, $data);
        $response->assertRedirect();

        $updated_lead = Lead::find($lead->id);
        $this->assertEquals('85.000', $updated_lead->net_value);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_validates_requiered()
    {
        $this->withOutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create(['dealership_id' => $dealership->id]);
        $this->actingAs($seller);
        $lead = factory(Lead::class)->create();
        $data = [
            'observation' => 'una observacion cualquiera',
        ];
        $store_route = route('leads.activities.store', $lead);
        $this->expectException(NotifiableException::class);
        $this->post($store_route, $data);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_can_has_files()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create(['dealership_id' => $dealership->id]);
        $this->actingAs($seller);
        $lead = factory(Lead::class)->create();
        $data = [
            'type' => ActivityTypes::Cotizar,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
            'net_value' => '85000',
            'files' => [UploadedFile::fake()->image('precios.jpg'), UploadedFile::fake()->image('precios2.jpg')],
        ];
        $store_route = route('leads.activities.store', $lead);
        $response = $this->post($store_route, $data);
        $response->assertRedirect();

        $updated_lead = Lead::find($lead->id);
        $this->assertEquals('85.000', $updated_lead->net_value);
    }
}
