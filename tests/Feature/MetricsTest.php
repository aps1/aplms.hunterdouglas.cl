<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Lead;
use App\Metrics\Metrics;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MetricsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_leads_count()
    {
        $metrics = new Metrics;

        factory(Lead::class, 50)->create([
            'created_at' => $this->faker->dateTimeBetween($this->getStart(), $this->getEnd()),
        ]);
        // previus_leads
        factory(Lead::class, 60)->create([
            'created_at' => $this->faker->dateTimeBetween($this->getStart(2), $this->getEnd(2)),
        ]);
        $filters = [];
        $leads_counts = $metrics->getLeadsCountByStatus($filters);
        $this->assertEquals($leads_counts['todos']['actual'], 50);
        $this->assertEquals($leads_counts['todos']['previus'], 60);
        $this->assertEquals($leads_counts['todos']['change'], '-16,7');
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_leads_count_by_status()
    {
        $metrics = new Metrics;
        $status = 'asignados';
        $status_id = LeadStatus::Asignado;

        factory(Lead::class, 50)->create([
            'status' => $status_id,
            'created_at' => $this->faker->dateTimeBetween($this->getStart(), $this->getEnd()),
        ]);
        // previus_leads
        factory(Lead::class, 60)->create([
            'status' => $status_id,
            'created_at' => $this->faker->dateTimeBetween($this->getStart(2), $this->getEnd(2)),
        ]);
        $filters = [];
        $leads_counts = $metrics->getLeadsCountByStatus($filters);
        $this->assertEquals($leads_counts[$status]['actual'], 50);
        $this->assertEquals($leads_counts[$status]['previus'], 60);
        $this->assertEquals($leads_counts[$status]['change'], '-16,7');
    }

    /**
     * Periodo por defecto
     *
     * @param  int  $previus
     * @return void
     */
    protected function getStart($previus = 1)
    {
        if ($previus > 1) {
            return Carbon::now()->subDays(90 * $previus);
        } else {
            return Carbon::now()->subDays(90);
        }
    }

    /**
     * Periodo por defecto end
     *
     * @param  int  $previus
     * @return void
     */
    protected function getEnd($previus = 1)
    {
        if ($previus > 1) {
            return Carbon::now()->subDays(90)->endOfDay();
        } else {
            return Carbon::now()->endOfDay();
        }
    }
}
