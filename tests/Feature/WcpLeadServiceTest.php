<?php

namespace Tests\Feature;

use App\Lead;
use App\Services\WcpLeadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WcpLeadServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_says_it_a_lead_should_be_sent_to_wcp()
    {
        $lead_cortinas = factory(Lead::class)->create([
            'message' => 'quiero cotizar cortinas',
            'country' => 'Chile',
        ]);
        $lead_cortinas_pe = factory(Lead::class)->create([
            'message' => 'quiero cotizar cortinas',
            'country' => 'PE',
        ]);
        $lead_ap = factory(Lead::class)->create([
            'message' => 'quiero cotizar ap',
            'country' => 'Chile',
        ]);
        $service = new WcpLeadService($lead_cortinas);
        $this->assertTrue($service->leadIsForWcp());
        $service = new WcpLeadService($lead_ap);
        $this->assertFalse($service->leadIsForWcp());
        $service = new WcpLeadService($lead_cortinas_pe);
        $this->assertFalse($service->leadIsForWcp());
    }

    /**
     * @test
     */
    public function it_store_lead_in_unified_database()
    {
        $lead_cortinas = factory(Lead::class)->create([
            'message' => 'quiero cotizar cortinas',
            'country' => 'Chile',
        ]);
        $service = new WcpLeadService($lead_cortinas);
        $id = $service->storeLeadInUnifiedDB();
        $this->assertNotNull($id);
    }
}
