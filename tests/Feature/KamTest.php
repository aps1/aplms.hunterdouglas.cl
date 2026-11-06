<?php

namespace Tests\Feature;

use App\Contact;
use App\Dealership;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ModelHelpers;
use Tests\TestCase;

class KamTest extends TestCase
{
    use ModelHelpers, RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_add_kams()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();

        $kam = factory(User::class)->state('kam')->make([
            'name' => 'Pedro Kam',
        ]);

        $data = [
            'name' => $kam->name,
            'email' => $kam->email,
            'cel' => '93 9849392',
            'cel_whatsapp' => '93 9849393',
            'password' => '56465321',
        ];

        $this->assertCount(0, User::kams()->get());

        $store_route = route('admin.kams.store');
        $response = $this->post($store_route, $data);
        $response->assertRedirect();
        $this->assertCount(1, User::kams()->get());
        $created_kam = User::where('email', $kam->email)->first();
        $this->assertEquals($created_kam->name, 'Pedro Kam');
        $this->assertEquals($created_kam->cel, '+56939849392');
        $this->assertEquals($created_kam->cel_whatsapp, '+56939849393');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_many_dealerships()
    {
        $this->assertHasMany('dealerships', Dealership::class, User::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_see_the_list_of_kams()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();

        $users = factory(User::class, 2)->create();
        $kams = factory(User::class, 3)->state('kam')->create();
        $response = $this->get('/admin/kams');
        $response->assertStatus(200)->assertSee($kams->first()->email);
        $response->assertStatus(200)->assertDontSee($users->first()->email);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_see_a_kam()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();

        $kam = factory(User::class)->state('kam')->create();
        $route = route('admin.kams.show', $kam->id);
        $response = $this->get($route);
        $response->assertStatus(200)->assertSee($kam->first()->email);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_edit_kam_dealers()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();

        $kam = factory(User::class)->state('kam')->create();
        factory(Dealership::class, 3)->create(['kam_id' => $kam->id]);
        $dealer1 = factory(Dealership::class)->create(['kam_id' => null]);
        $dealer2 = factory(Dealership::class)->create(['kam_id' => null]);
        $dealer3 = factory(Dealership::class)->create(['kam_id' => $kam->id]);
        $this->assertCount(4, $kam->dealerships);

        $route = route('admin.kams.dealerships', $kam->id);
        $selected_dealership = [$dealer1->id, $dealer2->id, $dealer3->id];
        $response = $this->put($route, ['dealerships' => $selected_dealership]);
        $response->assertRedirect();
        $updated_kam = User::where('id', $kam->id)->first();
        $this->assertCount(3, $updated_kam->dealerships);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_edit_kams()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();

        $kam = factory(User::class)->state('kam')->create([
            'name' => 'Pedro Kam',
            'email' => 'pedro@example.com',
        ]);
        $data = [
            'name' => 'nuevo nombre',
            'email' => 'Nuevo@example.com',
            'cel' => '976767656',
            'cel_whatsapp' => '976767654',
        ];

        $this->assertEquals($kam->name, 'Pedro Kam');

        $store_route = route('admin.kams.update', $kam->id);
        $response = $this->put($store_route, $data);
        $response->assertRedirect();
        $updated_kam = User::find($kam->id);
        $this->assertEquals($updated_kam->name, 'Nuevo Nombre');
        $this->assertEquals($updated_kam->email, 'nuevo@example.com');
        $this->assertEquals($updated_kam->cel, '+56976767656');
        $this->assertEquals($updated_kam->cel_whatsapp, '+56976767654');
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_ca_inactivate_kams()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();

        $kam = factory(User::class)->state('kam')->create([
            'name' => 'Pedro Kam',
            'email' => 'pedro@example.com',
            'is_active' => 0,
        ]);
        $this->assertTrue(! $kam->is_active);

        $activate_route = route('admin.kams.activate', $kam->id);
        $response = $this->post($activate_route, []);
        $response->assertRedirect();
        $updated_kam = User::find($kam->id);
        $this->assertFalse(! $updated_kam->is_active);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_kam_cannot_operate_on_admins()
    {
        $this->loggedInKam();
        $this->get(route('admin.admins'))->assertForbidden();

        $admin = factory(User::class)->state('admin')->create();
        // El KamScope le da 404 antes de que la utorizacion se revise
        $this->get(route('admin.admins.show', $admin->id))->assertNotFound();
        $this->put(route('admin.admins.update', $admin->id), [])->assertNotFound();
        $this->post(route('admin.admins.store'), [])->assertForbidden();
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_kam_cannot_operate_on_kams()
    {
        $this->loggedInKam();
        $this->get(route('admin.kams'))->assertForbidden();

        $kam = factory(User::class)->state('kam')->create();
        // El KamScope le da 404 antes de que la utorizacion se revise
        $this->get(route('admin.kams.show', $kam->id))->assertNotFound();
        $this->put(route('admin.kams.update', $kam->id), [])->assertNotFound();
        $this->post(route('admin.kams.store'), [])->assertForbidden();
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_kam_can_only_see_users()
    {
        // $this->withoutExceptionHandling();
        $this->loggedInKam();
        $this->get(route('users.index'))->assertOk();

        $dealership = factory(Dealership::class)->create();
        $user = factory(User::class)->state('seller')->create(['dealership_id' => $dealership->id]);
        $this->get(route('users.show', $user->id))->assertOk();
        $this->put(route('users.update', $user->id), [])->assertForbidden();
        $this->post(route('users.store'), [])->assertForbidden();
        $this->post(route('users.activate', $user->id), [])->assertForbidden();
        $this->post(route('users.inactivate', $user->id), [])->assertForbidden();
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_kam_can_only_see_dealerships()
    {
        // $this->withoutExceptionHandling();
        $kam = $this->loggedInKam();
        $this->withSession(['kam_id' => $kam->id]);
        $this->get(route('admin.dealerships'))->assertOk();

        $dealership = factory(Dealership::class)->create(['kam_id' => $kam->id]);
        $this->get(route('admin.dealerships.show', $dealership->id))->assertOk();
        $this->put(route('admin.dealerships.update', $dealership->id), [])->assertForbidden();
        $this->post(route('admin.dealerships.store'), [])->assertForbidden();
        $this->post(route('admin.dealerships.activate', $dealership->id), [])->assertForbidden();
        $this->post(route('admin.dealerships.inactivate', $dealership->id), [])->assertForbidden();
    }

    /**
     * a Kam can Ver, crear, derivar y comentar leads
     *
     * @return void
     *
     * @test
     */
    public function a_kam_can_manage_leads_without_destructive_operations()
    {
        // $this->withoutExceptionHandling();
        $kam = $this->loggedInKam();
        $delaership = factory(Dealership::class)->create(['kam_id' => $kam->id]);
        $this->get(route('admin.leads'))->assertOk();

        $lead = factory(Lead::class)->create(['dealership_id' => $delaership->id]);
        $this->get(route('admin.leads.show', $lead))->assertOk();
        $this->post(route('leads.store'), [])->assertStatus(302); // llega a la validacion, por lo tanto pasa la auth
        $this->put(route('admin.leads.update', $lead), [])->assertOk();
        $this->put(route('admin.leads.derive', $lead), [])->assertStatus(302);
        $this->put(route('admin.leads.close', $lead), [])->assertForbidden();
        $this->delete(route('admin.leads.destroy', $lead), [])->assertForbidden();
    }

    /**
     * a Kam can Ver, crear, derivar y comentar contactos
     *
     * @return void
     *
     * @test
     */
    public function a_kam_can_manage_contactos_without_destructive_operations()
    {
        // $this->withoutExceptionHandling();
        $kam = $this->loggedInKam();
        $delaership = factory(Dealership::class)->create(['kam_id' => $kam->id]);
        $this->get(route('contacts'))->assertOk();

        $contact = factory(Contact::class)->create(['dealership_id' => $delaership->id]);
        $this->get(route('contacts.show', $contact))->assertOk();
        $this->post(route('contacts.store'), [])->assertStatus(302); // llega a la validacion, por lo tanto pasa la auth
        $this->put(route('contacts.update', $contact), [])->assertForbidden();
        $this->delete(route('contacts.destroy', $contact), [])->assertForbidden();
    }
}
