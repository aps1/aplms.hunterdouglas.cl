<?php

namespace Tests\Feature\Owner;

use App\Dealership;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function it_index_active_and_inactive_sellers()
    {
        $this->withoutExceptionHandling();
        $owner = $this->loggedInOwner();
        $seller = factory(User::class)->create([
            'name' => 'Alberto',
            'is_active' => 1,
            'dealership_id' => $owner->dealership_id,
        ]);
        $another_seller = factory(User::class)->state('seller')->create([
            'name' => 'Pedrojsdkfjsdfhk',
            'is_active' => 0,
            'dealership_id' => $owner->dealership_id,
        ]);
        $index_route = route('users.index');
        $response = $this->get($index_route);
        $response->assertSeeText($seller->name);
        $response->assertSeeText($another_seller->name);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_authorize_show_seller()
    {
        // $this->withOutExceptionHandling();
        $owner = $this->loggedInOwner();
        $another_dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $owner->dealership_id,
        ]);
        $another_seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $another_dealership->id,
        ]);
        $route = route('users.show', $seller);
        $response = $this->get($route);
        $response->assertSuccessful();

        $route = route('users.show', $another_seller);
        $response = $this->get($route);
        $response->assertStatus(403);
    }
}
