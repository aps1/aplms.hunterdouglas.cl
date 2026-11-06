<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_login()
    {
        $this->withoutExceptionHandling();
        $data = ['email' => 'cualquiera@example.com', 'password' => bcrypt('123456')];
        factory(User::class)->create($data);
        $this->assertFalse(Auth::check());
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response = $this->post('/login', ['email' => 'cualquiera@example.com', 'password' => '123456']);
        $response->assertStatus(302);
        $this->assertTrue(Auth::check());

    }
}
