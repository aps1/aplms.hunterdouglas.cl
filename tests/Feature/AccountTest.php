<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function an_user_can_update_his_account_info()
    {
        $this->withOutExceptionHandling();
        $seller = factory(User::class)->state('seller')->create();
        $data = [
            'name' => 'nuevo nombre',
            'email' => $seller->email,
            'cel' => $seller->cel,
            'old_password' => null,
            'new_password' => null,
        ];
        $this->actingAs($seller);
        $response = $this->put(route('users.update', $seller), $data);
        $response->assertRedirect();
        $response->assertSessionHas('notification', [
            'type' => 'success',
            'message' => 'Se actualizaron los datos',
            'title' => 'Todo bien',
        ]);
        $user = User::find($seller->id);
        $this->assertEquals($user->name, 'Nuevo Nombre');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_validates_updates_account_info()
    {
        $seller = factory(User::class)->state('seller')->create([
            'password' => bcrypt('123456'),
        ]);
        $data = [
            'name' => 'nuevo nombre',
            'email' => $seller->email,
            'cel' => $seller->cel,
            'old_password' => '123456',
            'new_password' => '12345',
        ];
        $this->actingAs($seller);
        $response = $this->put(route('users.update', $seller), $data);
        $response->assertRedirect();
        $response->assertSessionHas('notification', [
            'type' => 'error',
            'message' => 'El campo new password debe contener al menos 6 caracteres.',
            'title' => null,
        ]);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_inactive_user_can_not_login()
    {
        $this->withoutExceptionHandling();
        $active_user = factory(User::class)->create(['is_active' => 1, 'password' => bcrypt('123456')]);
        $inactive_user = factory(User::class)->create(['is_active' => 0, 'password' => bcrypt('123456')]);

        $this->post('login', ['email' => $active_user->email, 'password' => '123456']);
        $this->assertAuthenticated();
        $this->post('logout');
        $this->assertFalse($this->isAuthenticated(), 'The user is authenticated');

        $this->expectException(ValidationException::class);
        $this->post('login', ['email' => $inactive_user->email, 'password' => '123456']);
        $this->assertFalse($this->isAuthenticated(), 'The user is authenticated');
    }
}
