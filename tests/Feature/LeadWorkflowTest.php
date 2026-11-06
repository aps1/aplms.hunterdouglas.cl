<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Lead;
use App\Workflows\LeadWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_activities_type_filtered_by_lead_status()
    {
        $leadWorkflow = new LeadWorkflow;
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->create(['status' => LeadStatus::Asignado]);
        $expected = [
            1 => 'Comentario',
        ];
        $activities = $leadWorkflow->getActivitiesForLead($lead);
        $this->assertEquals($activities, $expected);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_lead_can_be_reopen_from_perdido()
    {
        $leadWorkflow = new LeadWorkflow;
        $this->loggedInOwner();
        $lead = factory(Lead::class)->create(['status' => LeadStatus::Perdido]);
        $expected = [
            1 => 'Comentario',
            3 => 'Contacto',
            4 => 'Cotizar',
        ];
        $activities = $leadWorkflow->getActivitiesForLead($lead);
        $this->assertEquals($activities, $expected);
    }
}
