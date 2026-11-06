<?php

namespace Tests\Feature;

use App\Dealership;
use App\Enums\LeadOrigins;
use App\Enums\LeadStatus;
use App\Lead;
use App\Metrics\LeadsFilters;
use App\Repositories\LeadsRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeadsRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_leads_for_owner()
    {
        $dealership = factory(Dealership::class)->create();
        $another_dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->state('owner')->create([
            'dealership_id' => $dealership->id,
        ]);
        $leads = factory(Lead::class, 50)->create([
            'dealership_id' => $dealership->id,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);
        $another_leads = factory(Lead::class, 38)->create([
            'dealership_id' => $another_dealership->id,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);
        $previus_leads = factory(Lead::class, 40)->create([
            'dealership_id' => $dealership->id,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(2), $this->getDefaultEnd(2)),
        ]);

        $leadsRepository = new LeadsRepository;
        $filters = new LeadsFilters([]);
        $this->assertCount(128, Lead::all());
        $this->assertCount(50, $leadsRepository->getForOwner($owner, $filters));
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_leads_for_owner_filterd_by_origin()
    {
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->state('owner')->create([
            'dealership_id' => $dealership->id,
        ]);
        $leads = factory(Lead::class, 50)->create([
            'dealership_id' => $dealership->id,
            'origin' => LeadOrigins::Formulario,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);
        $another_leads = factory(Lead::class, 38)->create([
            'dealership_id' => $dealership->id,
            'origin' => LeadOrigins::Otros,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);

        $leadsRepository = new LeadsRepository;
        $filters = new LeadsFilters(['origin' => LeadOrigins::Otros]);
        $this->assertCount(88, Lead::all());
        $this->assertCount(38, $leadsRepository->getForOwner($owner, $filters));
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_leads_for_owner_filterd_by_status()
    {
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->state('owner')->create([
            'dealership_id' => $dealership->id,
        ]);
        $leads = factory(Lead::class, 50)->create([
            'dealership_id' => $dealership->id,
            'status' => LeadStatus::Cotizado,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);
        $another_leads = factory(Lead::class, 38)->create([
            'dealership_id' => $dealership->id,
            'status' => LeadStatus::Creado,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);

        $leadsRepository = new LeadsRepository;
        $filters = new LeadsFilters(['status' => LeadStatus::Creado]);
        $this->assertCount(88, Lead::all());
        $this->assertCount(38, $leadsRepository->getForUser($owner, $filters));
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_leads_for_seller()
    {
        $dealership = factory(Dealership::class)->create();
        $another_dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership->id,
        ]);
        $another_seller = factory(User::class)->state('seller')->create([
            'dealership_id' => $dealership->id,
        ]);
        $open_leads = factory(Lead::class, 50)->create([
            'dealership_id' => $dealership->id,
            'user_id' => null,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);
        $open_leads_another_dealer = factory(Lead::class, 30)->create([
            'dealership_id' => $another_dealership->id,
            'user_id' => null,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);
        $taken_leads = factory(Lead::class, 25)->create([
            'dealership_id' => $dealership->id,
            'user_id' => $seller->id,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);
        $taken_by_another_seller = factory(Lead::class, 25)->create([
            'dealership_id' => $dealership->id,
            'user_id' => $another_seller->id,
            'created_at' => $this->faker->dateTimeBetween($this->getDefaultStart(), $this->getDefaultEnd()),
        ]);

        $leadsRepository = new LeadsRepository;
        $filters = new LeadsFilters([]);
        $this->assertCount(130, Lead::all());
        $this->assertCount(75, $leadsRepository->getForUser($seller, $filters));
    }
}
