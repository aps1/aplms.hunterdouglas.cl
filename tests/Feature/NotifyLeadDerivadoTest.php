<?php

namespace Tests\Feature;

use App\Dealership;
use App\Enums\LeadStatus;
use App\Lead;
use App\Notifications\LeadDerivadoNotification;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyLeadDerivadoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function an_email_is_send_when_a_lead_is_derive_an_the_owner_doesnt_have_whatsapp()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();

        $lead = factory(Lead::class)->create([
            'dealership_id' => null,
            'status' => LeadStatus::Creado,
            'user_id' => null,
            'localization' => 'cl',
        ]);
        Notification::fake();
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->state('owner')->create([
            'dealership_id' => $dealership->id,
            'cel_whatsapp' => null,
            'is_active' => 1,
            'localization' => 'cl',
        ]);
        $data = ['dealership_id' => $dealership->id];
        $route = route('admin.leads.derive', $lead);

        $this->put($route, $data);
        Notification::assertSentTo(
            $owner,
            LeadDerivadoNotification::class,
            function ($notification, $channels) {
                return $channels == ['mail'];
            }
        );
    }
}
