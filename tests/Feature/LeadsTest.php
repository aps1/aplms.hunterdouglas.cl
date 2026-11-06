<?php

namespace Tests\Feature;

use App\Activity;
use App\Dealership;
use App\Enums\ActivityTypes;
use App\Enums\LeadOrigins;
use App\Enums\LeadSources;
use App\Enums\LeadStatus;
use App\Enums\ProductCategory;
use App\Events\LeadDerivado;
use App\Events\ManualLeadRegistered;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Tests\ModelHelpers;
use Tests\TestCase;

class LeadsTest extends TestCase
{
    use ModelHelpers, RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function it_belongs_to_a_dealership()
    {
        $this->assertBelongsTo('dealership', Dealership::class, Lead::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_belongs_to_an_user()
    {
        $this->assertBelongsTo('user', User::class, Lead::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_activities()
    {
        $this->assertHasMany('activities', Activity::class, Lead::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_see_the_list_of_leads()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $response = $this->get('/admin/leads');
        $response->assertStatus(200);
    }

    /**
     * @return void
     *
     * @test
     */
    public function only_admin_can_see_the_list_of_leads()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/admin/leads');
        $response->assertStatus(static::UNAUTHORIZE_CODE);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_status()
    {
        $this->withOutExceptionHandling();
        $lead = factory(Lead::class)->create(['status' => 1]);
        $this->assertEquals('Creado', $lead->status_description);
        $this->assertEquals('warning', $lead->status_color);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_origin()
    {
        $this->withOutExceptionHandling();
        $lead = factory(Lead::class)->create(['origin' => 1]);
        $this->assertEquals('Formulario', $lead->origin_description);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_produto_category()
    {
        $this->withOutExceptionHandling();
        $lead = factory(Lead::class)->create(['product_category' => 1]);
        $this->assertEquals('Productos Interiores', $lead->product_category_description);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_priority_field()
    {
        $this->withOutExceptionHandling();
        $lead = factory(Lead::class)->create(['priority' => 1]);
        $this->assertEquals('Baja', $lead->priority_description);
    }

    /**
     * @return void
     *
     * @test
     */
    public function users_can_change_the_lead_priority()
    {
        $this->withoutExceptionHandling();
        $lead = factory(Lead::class)->create(['priority' => 1]);
        $this->loggedInSeller();
        $data = ['priority' => 2];
        $uri = route('leads.priority', $lead);
        $this->put($uri, $data);

        $lead = Lead::find($lead->id);
        $this->assertEquals($lead->priority, 2);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_see_a_lead_info_pages()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        // El lead manual va a buscar actividad de creacion
        $lead = factory(Lead::class)->create(['origin' => LeadOrigins::Otros]);
        $show_route = route('admin.leads.show', $lead);
        $response = $this->get($show_route);
        $response->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_says_if_a_lead_is_closed()
    {
        $lead = factory(Lead::class)->create(['status' => LeadStatus::Perdido]);
        $open_lead = factory(Lead::class)->create(['status' => LeadStatus::Creado]);
        $this->assertTrue($lead->isClosed());
        $this->assertFalse($open_lead->isClosed());
        $this->assertTrue($open_lead->isOpen());
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_says_if_is_taken()
    {
        $lead_taken = factory(Lead::class)->create(['user_id' => factory(User::class)->create()->id]);
        $lead_free = factory(Lead::class)->create(['user_id' => null]);
        $this->assertTrue($lead_taken->isTaken());
        $this->assertTrue($lead_free->isFree());
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_derive_leads()
    {
        $this->withoutExceptionHandling();
        $user = $this->loggedInAdmin();
        $lead = factory(Lead::class)->create([
            'dealership_id' => null,
            'status' => LeadStatus::Creado,
            'user_id' => null,
        ]);
        $dealership = factory(Dealership::class)->create();
        factory(User::class, 2)->state('owner')->create([
            'dealership_id' => $dealership->id,
        ]);
        $this->assertEmpty($dealership->leads);
        $this->assertEmpty($lead->activities);
        $this->assertEmpty($lead->user);
        $data = ['dealership_id' => $dealership->id];
        $route = route('admin.leads.derive', $lead);
        $this->put($route, $data);
        $lead = Lead::find($lead->id);
        $this->assertEquals($dealership->id, $lead->dealership_id);
        $this->assertEquals($lead->status, LeadStatus::Derivado);
        $this->assertCount(1, $lead->activities);
        $this->assertEmpty($lead->user);

        $activity = $lead->activities()->first();
        $observation = $user->name.' derivó este prospecto al distribuidor '.$dealership->name;
        $this->assertEquals($activity->observation, $observation);
        $this->assertEquals($activity->type, ActivityTypes::Derivar);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_redirects_to_previus_url_after_derive()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->create([
            'dealership_id' => null,
            'status' => LeadStatus::Creado,
            'user_id' => null,
        ]);
        $dealership = factory(Dealership::class)->create();
        factory(User::class, 2)->state('owner')->create([
            'dealership_id' => $dealership->id,
        ]);

        $home_uri = route('admin.leads');
        $data = ['dealership_id' => $dealership->id, 'url_previus' => $home_uri];
        $route = route('admin.leads.derive', $lead);
        $response = $this->put($route, $data);
        $response->assertRedirect($home_uri);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_dispatchs_an_event_when_derived()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();

        $lead = factory(Lead::class)->create([
            'dealership_id' => null,
            'status' => LeadStatus::Creado,
            'user_id' => null,
        ]);
        $dealership = factory(Dealership::class)->create();
        factory(User::class, 2)->state('owner')->create([
            'dealership_id' => $dealership->id,
            'is_active' => 1,
        ]);
        Event::fake([LeadDerivado::class]);

        $data = ['dealership_id' => $dealership->id];
        $route = route('admin.leads.derive', $lead);
        $this->put($route, $data);

        Event::assertDispatched(LeadDerivado::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_email_is_not_send_to_inactive_owners()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        Mail::fake();

        $lead = factory(Lead::class)->create([
            'dealership_id' => null,
            'status' => LeadStatus::Creado,
            'user_id' => null,
        ]);
        $dealership = factory(Dealership::class)->create();
        factory(User::class, 2)->state('owner')->create([
            'dealership_id' => $dealership->id,
            'is_active' => 0,
        ]);
        $data = ['dealership_id' => $dealership->id];
        $route = route('admin.leads.update', $lead);

        $this->put($route, $data);
        // Assert that no mailables were sent...
        Mail::assertNothingSent();
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_delete_quotations()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->create();
        factory(Activity::class, 2)->create(['lead_id' => $lead->id]);
        $this->assertCount(1, Lead::all());
        $this->assertCount(2, Activity::all());
        $delete_route = route('admin.leads.destroy', $lead);
        $this->delete($delete_route);
        $this->assertCount(0, Lead::all());
        $this->assertCount(0, Activity::all());
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_store_a_lead_without_delaer()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
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
            'dealership_id' => null,
            'origin' => LeadOrigins::Manual,
            'source' => LeadSources::Facebook,
            'product_category' => ProductCategory::Otros,
        ];
        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();

        $this->assertCount(1, Lead::all());
        $this->assertCount(1, Activity::all());
        $lead = Lead::first();
        $this->assertEquals($lead->status, LeadStatus::Creado);
        $this->assertEquals($lead->source, LeadSources::Facebook);
        $this->assertEquals($lead->product_category, ProductCategory::Otros);
        $this->assertEquals($lead->product_category_description, 'Otros');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_dispatches_an_event_when_manual_leads_are_created()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
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
            'dealership_id' => null,
            'origin' => LeadOrigins::Manual,
            'source' => LeadSources::Facebook,
            'product_category' => ProductCategory::Otros,
        ];

        Event::fake([ManualLeadRegistered::class]);

        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();
        $this->assertCount(1, Lead::all());

        Event::assertDispatched(ManualLeadRegistered::class);

    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_store_a_lead_with_delaer()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->make();

        $this->assertCount(0, Lead::all());
        $this->assertCount(0, Activity::all());
        $dealership = factory(Dealership::class)->create();
        $data = [
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'address' => $lead->address,
            'geo_loc1' => $lead->geo_loc1,
            'geo_loc2' => $lead->geo_loc2,
            'geo_loc3' => $lead->geo_loc3,
            'brand' => $lead->brand,
            'dealership_id' => $dealership->id,
        ];
        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();

        $this->assertCount(1, Lead::all());
        $this->assertCount(2, Activity::all());
        $lead = Lead::first();
        // $this->assertEquals($lead->web_identifier, 'CLM00000001');
        $this->assertEquals($lead->status, LeadStatus::Derivado);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_validates_phone_without_email()
    {
        // $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->make();

        $this->assertCount(0, Lead::all());
        $this->assertCount(0, Activity::all());
        $dealership = factory(Dealership::class)->create();
        $data = [
            'name' => $lead->name,
            'email' => null,
            'phone' => null,
            'address' => $lead->address,
            'geo_loc1' => $lead->geo_loc1,
            'geo_loc2' => $lead->geo_loc2,
            'geo_loc3' => $lead->geo_loc3,
            'brand' => $lead->brand,
            'dealership_id' => $dealership->id,
        ];
        $url = route('leads.store');
        $response = $this->post($url, $data)->assertRedirect();

        $response->assertSessionHas('notification', [
            'type' => 'error',
            'message' => 'El campo email es obligatorio.',
            'title' => null,
        ]);

        $this->assertCount(0, Lead::all());
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_phone_accessor()
    {
        $this->withExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->make();
        $dealership = factory(Dealership::class)->create();

        $data = [
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => '7530158',
            'address' => $lead->address,
            'geo_loc1' => $lead->geo_loc1,
            'geo_loc2' => $lead->geo_loc2,
            'geo_loc3' => $lead->geo_loc3,
            'brand' => $lead->brand,
            'dealership_id' => $dealership->id,
        ];
        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();

        $created_lead = Lead::first();
        $this->assertEquals('+567530158', $created_lead->phone);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_phone_mutator()
    {
        $this->withExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->make();
        $dealership = factory(Dealership::class)->create();

        $data = [
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => '7530158',
            'address' => $lead->address,
            'geo_loc1' => $lead->geo_loc1,
            'geo_loc2' => $lead->geo_loc2,
            'geo_loc3' => $lead->geo_loc3,
            'brand' => $lead->brand,
            'dealership_id' => $dealership->id,
        ];
        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();

        $created_lead = DB::table('leads')->where('email', '=', $lead->email)->first();

        $this->assertEquals('+567530158', $created_lead->phone);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_exports_to_excel()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();

        Excel::shouldReceive('download')->once();
        $route = route('leads.export');
        $this->get($route);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_can_be_set_to_strategic()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->create(['priority' => null]);
        $this->assertFalse($lead->is_strategic);
        $route = route('leads.priority', $lead);
        $this->put($route, ['priority' => 4]);
        $updated_lead = Lead::find($lead->id);
        $this->assertTrue($updated_lead->is_strategic);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_dynamic_data_string_accessor()
    {
        $lead = factory(Lead::class)->create(['dynamic_data' => '[{"campo":"localidad","value":null},{"campo":"medio_de_contacto","value":"Email"}]']);
        $this->assertEquals($lead->dynamic_data_string, 'Localidad: ---; Medio de contacto: Email; ');

        $lead_whis_null_dynamic_data = factory(Lead::class)->create(['dynamic_data' => null]);
        $this->assertEquals($lead_whis_null_dynamic_data->dynamic_data_string, '');

        $lead_whith_empty_array = factory(Lead::class)->create(['dynamic_data' => '[]']);
        $this->assertEquals($lead_whith_empty_array->dynamic_data_string, '');

        $lead_whith_empty_string = factory(Lead::class)->create(['dynamic_data' => '']);
        $this->assertEquals($lead_whith_empty_string->dynamic_data_string, '');

        $lead_whith_checboxes = factory(Lead::class)->create(['dynamic_data' => '[{"campo":"checkboxes","value":[" Silhouette"," Luminette"]}]']);
        $this->assertEquals($lead_whith_checboxes->dynamic_data_string, '');
    }
}
