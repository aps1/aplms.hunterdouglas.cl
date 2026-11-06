<?php

namespace Tests\Feature;

use App\Contact;
use App\Dealership;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KamScopeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function it_scopes_dealerships_by_kam()
    {
        $kam1 = factory(User::class)->state('kam')->create();
        $dealership1 = factory(Dealership::class)->create(['kam_id' => $kam1->id]);
        $dealership2 = factory(Dealership::class)->create();
        $this->assertCount(2, Dealership::all());
        $this->withSession(['kam_id' => $kam1->id]);
        $this->assertCount(1, Dealership::all());
    }

    /**
     * Puede ver todos los usuarios de sus dealersites,
     *
     * @return void
     *
     * @test
     */
    public function it_scopes_users_by_kam()
    {
        $kam1 = factory(User::class)->state('kam')->create();
        $kam2 = factory(User::class)->state('kam')->create();
        $dealership1 = factory(Dealership::class)->create(['kam_id' => $kam1->id]);
        $dealership2 = factory(Dealership::class)->create(['kam_id' => $kam2->id]);
        $seller1 = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership1->id,
        ]);
        $seller2 = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership1->id,
        ]);
        $seller3 = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership2->id,
        ]);
        $this->assertCount(3, User::sellers()->get());
        $this->withSession(['kam_id' => $kam1->id]);
        $this->assertCount(2, User::sellers()->get());
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_scopes_leads_by_kam_dealership()
    {
        $kam1 = factory(User::class)->state('kam')->create();
        $kam2 = factory(User::class)->state('kam')->create();
        $dealership1 = factory(Dealership::class)->create(['kam_id' => $kam1->id]);
        $dealership2 = factory(Dealership::class)->create(['kam_id' => $kam2->id]);
        factory(Lead::class, 4)->create([
            'dealership_id' => $dealership1->id,
        ]);
        factory(Lead::class, 2)->create([
            'dealership_id' => $dealership2->id,
        ]);
        factory(Lead::class, 3)->create([
            'dealership_id' => null,
            'status' => 1, // creado puede ver
        ]);
        factory(Lead::class, 1)->create([
            'dealership_id' => null,
            'status' => 8, // clausurado no puede ver
        ]);
        factory(Lead::class, 1)->create([
            'dealership_id' => null,
            'status' => 10, // no requiere accion no puede ver
        ]);
        $this->assertCount(11, Lead::all());
        $this->withSession(['kam_id' => $kam1->id]);
        $this->assertCount(7, Lead::all());
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_scopes_contacts_by_kam_dealership()
    {
        $kam1 = factory(User::class)->state('kam')->create();
        $kam2 = factory(User::class)->state('kam')->create();
        $dealership1 = factory(Dealership::class)->create(['kam_id' => $kam1->id]);
        $dealership2 = factory(Dealership::class)->create(['kam_id' => $kam2->id]);
        factory(Contact::class, 4)->create([
            'dealership_id' => $dealership1->id,
        ]);
        factory(Contact::class, 2)->create([
            'dealership_id' => $dealership2->id,
        ]);
        $this->assertCount(6, Contact::all());
        $this->withSession(['kam_id' => $kam1->id]);
        $this->assertCount(4, Contact::all());
    }
}
