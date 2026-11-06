<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_see_the_list_of_admins()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();

        $users = factory(User::class, 15)->create();
        $admins = factory(User::class, 3)->state('admin')->create();
        $response = $this->get('/admin/admins');
        $response->assertStatus(200)->assertSee($admins->first()->email);
        $response->assertStatus(200)->assertDontSee($users->first()->email);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_add_another_admin()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();

        $admin = factory(User::class)->make([
            'name' => 'Pedro Admin',
        ]);

        $data = [
            'name' => $admin->name,
            'email' => $admin->email,
            'cel' => '93 9849392',
            'cel_whatsapp' => '93 9849393',
            'password' => '56465321',
        ];

        $this->assertCount(1, User::admins()->get());

        $store_route = route('admin.admins.store');
        $response = $this->post($store_route, $data);
        $response->assertRedirect();
        $this->assertCount(2, User::admins()->get());
        $created_admin = User::where('email', $admin->email)->first();
        $this->assertEquals($created_admin->name, 'Pedro Admin');
        $this->assertEquals($created_admin->cel, '+56939849392');
        $this->assertEquals($created_admin->cel_whatsapp, '+56939849393');
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_edit_another_admin()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();

        $admin = factory(User::class)->state('admin')->create([
            'name' => 'Pedro Admin',
            'email' => 'pedro@example.com',
            'cel' => '+56898445689',
            'cel_whatsapp' => '+56898445683',
        ]);
        $data = [
            'name' => 'nuevo nombre',
            'email' => 'Nuevo@example.com',
            'cel' => '898445688',
            'cel_whatsapp' => '898445683',
        ];

        $this->assertCount(2, User::admins()->get());
        $this->assertEquals($admin->name, 'Pedro Admin');

        $store_route = route('admin.admins.update', $admin->id);
        $response = $this->put($store_route, $data);
        $response->assertRedirect();
        $this->assertCount(2, User::admins()->get());
        $updated_admin = User::find($admin->id);
        $this->assertEquals($updated_admin->name, 'Nuevo Nombre');
        $this->assertEquals($updated_admin->email, 'nuevo@example.com');
        $this->assertEquals($updated_admin->cel, '+56898445688');
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_see_another_admin()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();

        $admin = factory(User::class)->state('admin')->create([
            'name' => 'Pedro Admin',
            'email' => 'pedro@example.com',
        ]);

        $this->assertCount(2, User::admins()->get());

        $show_route = route('admin.admins.show', $admin->id);
        $response = $this->get($show_route);
        $response->assertOk();
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_inactivate_admins()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $admin = factory(User::class)->state('admin')->create(['is_active' => 1]);
        $this->assertTrue($admin->is_active);
        $inactivate_route = route('admin.admins.inactivate', $admin);
        $this->post($inactivate_route);
        $inactive_admin = User::find($admin->id);
        $this->assertFalse($inactive_admin->is_active);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_inactivate_users()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $seller = factory(User::class)->state('seller')->create(['is_active' => 1]);
        $this->assertTrue($seller->is_active);
        $inactivate_route = route('users.inactivate', $seller);
        $this->post($inactivate_route);
        $inactive_user = User::find($seller->id);
        $this->assertFalse($inactive_user->is_active);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_activate_users()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $seller = factory(User::class)->state('seller')->create(['is_active' => 0]);
        $this->assertFalse($seller->is_active);
        $activate_route = route('users.activate', $seller);
        $this->post($activate_route);
        $active_user = User::find($seller->id);
        $this->assertTrue($active_user->is_active);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_not_see_the_admin_panel()
    {
        $seller = factory(User::class)->state('seller')->create();
        $this->actingAs($seller)
            ->get('admin/dealerships')
            ->assertNotFound();
    }
}
