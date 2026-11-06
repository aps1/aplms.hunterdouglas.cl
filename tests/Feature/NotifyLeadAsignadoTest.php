<?php

namespace Tests\Feature;

use App\Dealership;
use App\Enums\LeadStatus;
use App\Lead;
use App\Notifications\LeadAsignadoNotification;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyLeadAsignadoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function an_email_is_send_when_a_lead_is_asignado_an_the_user_doesnt_have_whatsapp()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->state('owner')->create(['dealership_id' => $dealership->id]);
        $seller1 = factory(User::class)->state('seller')->create([
            'dealership_id' => $owner->dealershipid,
            'is_active' => 1,
            'cel_whatsapp' => null,
        ]);
        $this->actingAs($owner);
        $lead = factory(Lead::class)->create([
            'dealership_id' => $dealership->id,
            'user_id' => null,
            'status' => LeadStatus::Derivado,
            'localization' => 'cl',
        ]);

        // Assert that no mailables were sent...
        Notification::fake();

        $route = route('owner.leads.update', $lead);
        $data = ['user_id' => $seller1->id];
        $this->put($route, $data);

        Notification::assertSentTo(
            $seller1,
            LeadAsignadoNotification::class,
            function ($notification, $channels) {
                return $channels == ['mail'];
            }
        );
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_whatsapp_is_send_when_a_lead_is_asignado_an_the_user_have_whatsapp()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->state('owner')->create(['dealership_id' => $dealership->id]);
        $seller1 = factory(User::class)->state('seller')->create([
            'dealership_id' => $owner->dealershipid,
            'is_active' => 1,
            'cel_whatsapp' => '+56976833067',
        ]);
        $this->actingAs($owner);
        $lead = factory(Lead::class)->create([
            'dealership_id' => $dealership->id,
            'user_id' => null,
            'status' => LeadStatus::Derivado,
            'localization' => 'cl',
        ]);

        // Assert that no mailables were sent...
        Notification::fake();

        $route = route('owner.leads.update', $lead);
        $data = ['user_id' => $seller1->id];
        $this->put($route, $data);

        Notification::assertSentTo(
            $seller1,
            LeadAsignadoNotification::class,
        );
    }
}
