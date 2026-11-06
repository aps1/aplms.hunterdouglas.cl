<?php

namespace Tests\Feature;

use App\Activity;
use App\Dealership;
use App\Enums\ActivityTypes;
use App\Enums\UserTypes;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultitenantTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function it_sets_localization_session_after_login()
    {
        $this->withoutExceptionHandling();
        factory(User::class)->create([
            'name' => 'pedro',
            'email' => 'pedro@example.com',
            'password' => bcrypt('123456'),
            'localization' => 'br',
        ]);
        $this->assertFalse(auth()->check());
        $this->post(route('login'), ['email' => 'pedro@example.com', 'password' => '123456']);
        $this->assertTrue(auth()->check());
        $this->assertTrue(session('localization') == 'br');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_scopes_users_by_localization()
    {
        $chile_admin = factory(User::class)->states('admin', 'cl')->create();
        $argentina_admin = factory(User::class)->states('admin', 'ar')->create();
        factory(User::class, 4)->states('seller', 'cl')->create();
        factory(User::class, 5)->states('seller', 'ar')->create();

        $this->loggedInAdmin($chile_admin);
        $sellers = User::sellers()->get();
        $this->assertCount(4, $sellers);

        $this->loggedInAdmin($argentina_admin);
        $sellers = User::sellers()->get();
        $this->assertCount(5, $sellers);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_adds_localization_at_users_creating()
    {
        $argentina_admin = factory(User::class)->states('admin', 'ar')->create();

        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $data = [
            'name' => 'Pedro Perez',
            'email' => 'pedro@example.com',
            'password' => '123456',
            'cel' => '939849392',
            'cel_whatsapp' => '939849392',
            'dealership_id' => $dealership->id,
            'type' => UserTypes::Seller,
        ];
        $this->loggedInAdmin($argentina_admin);
        $response = $this->post(route('users.store'), $data);
        $response->assertRedirect();
        $pedro = User::where('email', 'pedro@example.com')->first();
        $this->assertEquals($pedro->localization, 'ar');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_scopes_dealers_by_localization()
    {
        $chile_admin = factory(User::class)->states('admin', 'cl')->create();
        $argentina_admin = factory(User::class)->states('admin', 'ar')->create();
        factory(Dealership::class, 4)->create(['localization' => 'cl']);
        factory(Dealership::class, 5)->create(['localization' => 'ar']);

        $this->loggedInAdmin($chile_admin);
        $dealers = Dealership::all();
        $this->assertCount(4, $dealers);

        $this->loggedInAdmin($argentina_admin);
        $dealers = Dealership::all();
        $this->assertCount(5, $dealers);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_adds_localization_at_dealer_creating()
    {
        $argentina_admin = factory(User::class)->states('admin', 'ar')->create();
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->make();

        $data = [
            'name' => $dealership->name,
            'identifier' => null,
            'region' => $dealership->region,
            'commune' => $dealership->commune,
            'address' => $dealership->address,
            'wcp1' => '1',
        ];
        $this->loggedInAdmin($argentina_admin);
        $store_route = route('admin.dealerships.store', $dealership);
        $this->post($store_route, $data);
        $dealer = Dealership::where('name', $dealership->name)->first();
        $this->assertEquals($dealer->localization, 'ar');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_scopes_leads_by_localization()
    {
        $chile_admin = factory(User::class)->states('admin', 'cl')->create();
        $argentina_admin = factory(User::class)->states('admin', 'ar')->create();
        factory(Lead::class, 4)->create(['localization' => 'cl']);
        factory(Lead::class, 5)->create(['localization' => 'ar']);

        $this->loggedInAdmin($chile_admin);
        $leads = Lead::all();
        $this->assertCount(4, $leads);

        $this->loggedInAdmin($argentina_admin);
        $leads = Lead::all();
        $this->assertCount(5, $leads);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_adds_localization_at_leads_creating()
    {
        $argentina_admin = factory(User::class)->states('admin', 'ar')->create();
        $this->withoutExceptionHandling();
        $lead = factory(Lead::class)->make();

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
        ];
        $this->loggedInAdmin($argentina_admin);
        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();

        $created_lead = Lead::where('name', $lead->name)->first();
        $this->assertEquals($created_lead->localization, 'ar');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_adds_localization_at_activity_creating()
    {
        $argentina_admin = factory(User::class)->states('admin', 'ar')->create();
        $this->withoutExceptionHandling();

        $lead = factory(Lead::class)->create(['localization' => 'ar']);
        $data = [
            'type' => ActivityTypes::Comentario,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
        ];
        $this->loggedInAdmin($argentina_admin);

        $store_route = route('leads.activities.store', $lead);
        $this->post($store_route, $data);

        $activity = Activity::where('lead_id', $lead->id)->first();
        $this->assertEquals($activity->localization, 'ar');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_scopes_activities_by_localization()
    {
        $chile_admin = factory(User::class)->states('admin', 'cl')->create();
        $argentina_admin = factory(User::class)->states('admin', 'ar')->create();
        factory(Activity::class, 4)->create(['localization' => 'cl']);
        factory(Activity::class, 5)->create(['localization' => 'ar']);

        $this->loggedInAdmin($chile_admin);
        $activities = Activity::all();
        $this->assertCount(4, $activities);

        $this->loggedInAdmin($argentina_admin);
        $activities = Activity::all();
        $this->assertCount(5, $activities);
    }
}
