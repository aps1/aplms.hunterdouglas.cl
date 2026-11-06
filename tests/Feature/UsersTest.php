<?php

namespace Tests\Feature;

use App\Activity;
use App\Dealership;
use App\Enums\UserTypes;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\ModelHelpers;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use ModelHelpers, RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function it_scopes_active_users()
    {
        $this->withOutExceptionHandling();
        factory(User::class, 2)->create(['is_active' => 1]);
        factory(User::class, 3)->create(['is_active' => 0]);
        $this->assertCount(2, User::active()->get());
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_belongs_to_dealership()
    {
        $this->assertBelongsTo('dealership', Dealership::class, User::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_many_leads()
    {
        $this->assertHasMany('leads', Lead::class, User::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_says_if_a_user_is_of_type()
    {
        $admin = factory(User::class)->state('admin')->create();
        $this->assertTrue($admin->isAdmin());

        $owner = factory(User::class)->state('owner')->create();
        $this->assertTrue($owner->isOwner());
        $this->assertTrue($owner->isExecutiveLevel());

        $seller = factory(User::class)->state('seller')->create();
        $this->assertTrue($seller->isSeller());
        $this->assertFalse($seller->isExecutiveLevel());

        $manager = factory(User::class)->state('manager')->create();
        $this->assertTrue($manager->isManager());
        $this->assertTrue($manager->isExecutiveLevel());
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_type_description()
    {
        $admin = factory(User::class)->state('admin')->create();
        $this->assertEquals($admin->type_description, 'Administrador');

        $owner = factory(User::class)->state('owner')->create();
        $this->assertEquals($owner->type_description, 'Dueño(a)');

        $seller = factory(User::class)->state('seller')->create();
        $this->assertEquals($seller->type_description, 'Vendedor(a)');

        $manager = factory(User::class)->state('manager')->create();
        $this->assertEquals($manager->type_description, 'Encargado(a)');
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_see_the_list_of_users()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();

        $users = factory(User::class, 15)->create();
        $admins = factory(User::class, 3)->state('admin')->create();
        $response = $this->get('users');
        $response
            ->assertStatus(200)
            ->assertSee($users->first()->email)
            ->assertDontSee($admins->first()->email);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_scopes_admin_users()
    {
        $this->withOutExceptionHandling();
        factory(User::class, 2)->states('admin')->create();
        factory(User::class, 3)->states('seller')->create();
        $this->assertCount(2, User::admins()->get());
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_view_a_users_info_page()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $dealership = factory(Dealership::class)->create();
        $user = factory(User::class)->create(['dealership_id' => $dealership->id]);
        $lead = factory(Lead::class)->create([
            'dealership_id' => $dealership->id,
            'user_id' => $user->id,
        ]);
        factory(Activity::class)->create([
            'dealership_id' => $dealership->id,
            'user_id' => $user->id,
            'lead_id' => $lead->id,
        ]);
        $show_route = route('users.show', $user);
        $response = $this->get($show_route);
        $response->assertSuccessful();
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_add_personal_to_dealership()
    {
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
        $this->loggedInAdmin();
        $this->assertCount(1, User::all());
        $response = $this->post(route('users.store'), $data);
        $response->assertRedirect();

        $this->assertCount(2, User::all());
        $this->assertCount(1, User::sellers()->get());
        $this->assertCount(1, $dealership->users);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_validates_add_personal_to_dealership()
    {
        $dealership = factory(Dealership::class)->create();
        $data = [
            'name' => 'Pedro Perez',
            'email' => 'pedro@example.com',
            'password' => '12345',
            'cel' => '939849392',
            'cel_whatsapp' => '939849392',
            'dealership_id' => $dealership->id,
            'type' => UserTypes::Seller,
        ];
        $this->loggedInAdmin();
        $this->assertCount(1, User::all());
        $response = $this->post(route('users.store'), $data);
        $response->assertRedirect();
        $response->assertSessionHas('notification', [
            'type' => 'error',
            'message' => 'El campo password debe contener al menos 6 caracteres.',
            'title' => null,
        ]);
        $this->assertCount(1, User::all());
        $this->assertCount(0, User::sellers()->get());
        $this->assertCount(0, $dealership->users);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_and_owner_can_edit_personal()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $user = factory(User::class)->create([
            'name' => 'Pedro Perez',
            'dealership_id' => $dealership->id,
            'type' => UserTypes::Seller,
        ]);
        $data = [
            'name' => 'nuevo nombre',
            'email' => $user->email,
            'cel' => $user->cel,
            'cel_whatsapp' => '976565656',
            'type' => UserTypes::Manager,
        ];
        // $this->loggedInSeller();
        // $this->loggedInAdmin();
        $this->loggedInOwner(null, $dealership);
        $this->assertCount(2, User::all());
        $response = $this->put(route('users.update', $user), $data);
        $response->assertRedirect();
        $updated_user = User::where('email', $user->email)->first();
        $this->assertEquals($updated_user->name, 'Nuevo Nombre');
        $this->assertEquals($updated_user->type, UserTypes::Manager);
        $this->assertEquals($updated_user->cel_whatsapp, '+56976565656');

        $this->assertCount(2, User::all());
        $this->assertCount(0, User::sellers()->get());
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_cel_accessors()
    {
        $this->withExceptionHandling();
        $this->loggedInAdmin();
        $user = factory(User::class)->create(['cel' => '+56 9398 49392']);
        $this->assertEquals('+56939849392', $user->cel);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_has_cel_mutator()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $data = [
            'name' => 'Pedro Perez',
            'email' => 'pedro@example.com',
            'password' => '123456',
            'cel' => '93 9849392',
            'cel_whatsapp' => '93 9849393',
            'dealership_id' => $dealership->id,
            'type' => UserTypes::Seller,
        ];
        $this->loggedInAdmin();
        $response = $this->post(route('users.store'), $data);
        $response->assertRedirect();
        $created_user = DB::table('users')->where('email', 'pedro@example.com')->first();
        $this->assertEquals('+56939849392', $created_user->cel);
        $this->assertEquals('+56939849393', $created_user->cel_whatsapp);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_activate_users_if_theirs_dealer_is_active()
    {
        $dealership = factory(Dealership::class)->create(['is_active' => 1]);
        $seller = factory(User::class)->create(['is_active' => 0, 'dealership_id' => $dealership->id]);
        $this->assertFalse($seller->is_active);
        $this->loggedInAdmin();
        $response = $this->post(route('users.activate', $seller), []);
        $response->assertRedirect();
        $updated_seller = User::find($seller->id);
        $this->assertTrue($updated_seller->is_active);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_not_activate_users_if_theirs_dealer_is_inactive()
    {
        $dealership = factory(Dealership::class)->create(['is_active' => 0]);
        $seller = factory(User::class)->create(['is_active' => 0, 'dealership_id' => $dealership->id]);
        $this->assertFalse($seller->is_active);
        $this->loggedInAdmin();
        $response = $this->post(route('users.activate', $seller), []);
        $response->assertRedirect();
        $updated_seller = User::find($seller->id);
        $this->assertFalse($updated_seller->is_active);
    }
}
