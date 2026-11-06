<?php

namespace Tests\Feature;

use App\Dealership;
use App\Enums\UserTypes;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ModelHelpers;
use Tests\TestCase;

class DealershipsTest extends TestCase
{
    use ModelHelpers, RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function it_has_many_users()
    {
        $this->assertHasMany('users', User::class, Dealership::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_many_leads()
    {
        $this->assertHasMany('leads', Lead::class, Dealership::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_belongs_to_kam()
    {
        $this->assertBelongsTo('kam', User::class, Dealership::class, 'kam_id');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_many_sellers()
    {
        $dealership = factory(Dealership::class)->create();
        factory(User::class, 4)->state('seller')->create(['dealership_id' => $dealership->id]);
        factory(User::class, 2)->state('owner')->create(['dealership_id' => $dealership->id]);
        $this->assertCount(4, $dealership->sellers);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_many_owners()
    {
        $this->withOutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        factory(User::class, 4)->state('owner')->create(['dealership_id' => $dealership->id]);
        factory(User::class, 2)->state('seller')->create(['dealership_id' => $dealership->id]);
        $this->assertCount(4, $dealership->owners);
    }

    /**
     * owners y encargados
     *
     * @return void
     *
     * @test
     */
    public function it_has_many_executives()
    {
        $this->withOutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        factory(User::class, 4)->state('owner')->create(['dealership_id' => $dealership->id]);
        factory(User::class, 3)->state('manager')->create(['dealership_id' => $dealership->id]);
        factory(User::class, 2)->state('seller')->create(['dealership_id' => $dealership->id]);
        $this->assertCount(7, $dealership->executives);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_list_all_leaderships()
    {
        $this->withOutExceptionHandling();
        factory(Dealership::class, 4)->create();
        $this->loggedInAdmin();
        $response = $this->get('admin/dealerships');
        $response->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_view_a_dealership_info_page()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $dealership = factory(Dealership::class)->create();
        $show_route = route('admin.dealerships.show', $dealership);
        $response = $this->get($show_route);
        $response->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_edit_the_dealership()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $kam = factory(User::class)->state('kam')->create();
        $dealership = factory(Dealership::class)->create([
            'name' => 'para cambiar',
            'kam_id' => $kam->id,
        ]);

        $new_kam = factory(User::class)->state('kam')->create();

        $data = [
            'name' => 'nuevo name',
            'identifier' => $dealership->identifier,
            'region' => $dealership->region,
            'commune' => $dealership->commune,
            'address' => $dealership->address,
            'phone' => $dealership->phone,
            'email' => $dealership->email,
            'kam_id' => $new_kam->id,
        ];

        $this->assertEquals($dealership->name, 'para cambiar');
        $this->assertEquals($dealership->kam->name, $kam->name);
        $update_route = route('admin.dealerships.update', $dealership);
        $response = $this->put($update_route, $data);
        $response->assertRedirect();
        $updated_dealership = Dealership::find($dealership->id);
        $this->assertEquals($updated_dealership->name, 'nuevo name');
        $this->assertEquals($updated_dealership->kam->name, $new_kam->name);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_create_a_dealership()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $dealership = factory(Dealership::class)->make(['name' => 'para cambiar']);
        $kam = factory(User::class)->state('kam')->create();

        $data = [
            'name' => $dealership->name,
            'identifier' => null,
            'region' => $dealership->region,
            'commune' => $dealership->commune,
            'address' => $dealership->address,
            'wcp1' => '1',
            'kam_id' => $kam->id,
        ];

        $this->assertCount(0, Dealership::all());
        $store_route = route('admin.dealerships.store', $dealership);
        $response = $this->post($store_route, $data);
        $response->assertRedirect();
        $this->assertCount(1, Dealership::all());
        $created_dealership = Dealership::first();
        $this->assertEquals($created_dealership->name, $dealership->name);
        $this->assertEquals($created_dealership->kam->name, $kam->name);
        $this->assertTrue($created_dealership->wcp1);
        $this->assertfalse($created_dealership->wcp2);
        $this->assertEquals(['HunterDouglas'], $created_dealership->brands());
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_validates_update_form()
    {
        $this->loggedInAdmin();

        $dealership = factory(Dealership::class)->create();
        $data = [
            'id' => '238429',
            'name' => null,
        ];
        $update_route = route('admin.dealerships.update', $dealership);
        $response = $this->put($update_route, $data);
        $response->assertSessionHas('notification', [
            'type' => 'error',
            'message' => 'El campo name es obligatorio.',
            'title' => null,
        ]);
        // $response->assertSessionHasNotificationErrors(['name']);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_inactivate_dealerships()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $dealership = factory(Dealership::class)->create(['is_active' => 1]);
        $this->assertTrue($dealership->is_active);
        $inactivate_route = route('admin.dealerships.inactivate', $dealership);
        $this->post($inactivate_route);
        $inactive_dealership = Dealership::find($dealership->id);
        $this->assertFalse($inactive_dealership->is_active);
    }

    /**
     * @return void
     *
     * @test
     */
    public function all_users_became_inactive_with_the_dealership()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $dealership = factory(Dealership::class)->create(['is_active' => 1]);
        $users = factory(User::class, 5)->create(['dealership_id' => $dealership->id, 'is_active' => 1]);
        $this->assertTrue($dealership->is_active);
        $this->assertTrue($users->first()->is_active);
        $inactivate_route = route('admin.dealerships.inactivate', $dealership);
        $response = $this->post($inactivate_route);
        $inactive_dealership = Dealership::find($dealership->id);
        $inactive_user = User::find($users->first()->id);
        $this->assertFalse($inactive_dealership->is_active);
        $this->assertFalse($inactive_user->is_active);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_activate_dealerships()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $dealership = factory(Dealership::class)->create(['is_active' => 0]);
        $this->assertFalse($dealership->is_active);
        $activate_route = route('admin.dealerships.activate', $dealership);
        $response = $this->post($activate_route);
        $active_dealership = Dealership::find($dealership->id);
        $this->assertTrue($active_dealership->is_active);
    }

    /**
     * @return void
     *
     * @test
     */
    public function not_all_user_became_active_with_the_dealership()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $dealership = factory(Dealership::class)->create(['is_active' => 0]);
        $users = factory(User::class, 5)->create(['dealership_id' => $dealership->id, 'is_active' => 0]);
        $this->assertFalse($dealership->is_active);
        $this->assertFalse($users->first()->is_active);
        $activate_route = route('admin.dealerships.activate', $dealership);
        $this->post($activate_route);
        $active_dealership = Dealership::find($dealership->id);
        $active_user = User::find($users->first()->id);
        $this->assertTrue($active_dealership->is_active);
        $this->assertFalse($active_user->is_active);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_contact_info_for_the_first_relevant_user()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $dealership = factory(Dealership::class)->create();
        $inactive_manager = factory(User::class)->create([
            'type' => UserTypes::Manager,
            'is_active' => 0,
            'dealership_id' => $dealership->id,
        ]);
        $active_manager = factory(User::class)->create([
            'type' => UserTypes::Manager,
            'is_active' => 1,
            'dealership_id' => $dealership->id,
        ]);
        $owner = factory(User::class)->create([
            'type' => UserTypes::Owner,
            'is_active' => 1,
            'dealership_id' => $dealership->id,
        ]);
        $conctacResponsable = $dealership->getContactResponsable();
        $this->assertEquals($conctacResponsable->id, $active_manager->id);

    }
}
