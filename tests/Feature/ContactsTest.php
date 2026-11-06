<?php

namespace Tests\Feature;

use App\Activity;
use App\Contact;
use App\Dealership;
use App\Enums\ActivityTypes;
use App\Enums\ContactTypes;
use App\Enums\LeadStatus;
use App\Lead;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_see_the_list_of_contacts()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        factory(Contact::class, 5)->create();
        $response = $this->get('/contacts');
        $response->assertStatus(200);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_see_a_contact()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $contact = factory(Contact::class)->create();
        $lead1 = factory(Lead::class)->create(['contact_id' => $contact->id]);
        $lead2 = factory(Lead::class)->create(['contact_id' => $contact->id]);
        factory(Activity::class, 2)->create(['lead_id' => $lead1->id]);
        factory(Activity::class, 3)->create(['lead_id' => $lead2->id]);

        $response = $this->get(route('contacts.show', $contact));
        $response->assertStatus(200);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_gets_leads_activities()
    {
        $contact = factory(Contact::class)->create();
        $lead1 = factory(Lead::class)->create(['contact_id' => $contact->id]);
        $lead2 = factory(Lead::class)->create(['contact_id' => $contact->id]);
        factory(Activity::class, 2)->create(['lead_id' => $lead1->id]);
        factory(Activity::class, 3)->create(['lead_id' => $lead2->id]);

        $this->assertCount(5, $contact->getActivities());
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_new_contact_is_created_when_a_new_lead_is_registered_with_dealership()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->make();
        $dealership = factory(Dealership::class)->create();
        $data = [
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'address' => $lead->address,
            'geo_loc1' => $lead->geo_loc1,
            'geo_loc2' => $lead->geo_loc2,
            'geo_loc3' => $lead->geo_loc3,
            'brand' => $lead->brand,
            'dealership_id' => $dealership->id,
        ];
        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();
        $this->assertCount(1, Contact::all());
        $updated_lead = Lead::where('email', $lead->email)->first();
        $this->assertEquals(Contact::first()->id, $updated_lead->contact_id);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_lead_is_asociated_to_contact_if_it_already_exists_for_dealership()
    {
        $this->withOutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->make();
        $dealership = factory(Dealership::class)->create();
        $contact = factory(Contact::class)->create([
            'email' => $lead->email,
            'dealership_id' => $dealership->id,
        ]);
        $data = [
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'address' => $lead->address,
            'geo_loc1' => $lead->geo_loc1,
            'geo_loc2' => $lead->geo_loc2,
            'geo_loc3' => $lead->geo_loc3,
            'brand' => $lead->brand,
            'dealership_id' => $dealership->id,
        ];
        $url = route('leads.store');
        $this->post($url, $data)->assertRedirect();
        $this->assertCount(1, Contact::all());
        $created_lead = Lead::where('email', $lead->email)->first();
        $this->assertEquals($contact->id, $created_lead->contact_id);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_new_contact_is_created_when_a_new_lead_is_derived_to_dealership()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->create([
            'dealership_id' => null,
            'status' => LeadStatus::Creado,
            'user_id' => null,
        ]);
        $dealership = factory(Dealership::class)->create();
        factory(User::class, 2)->state('owner')->create([
            'dealership_id' => $dealership->id,
        ]);
        $this->assertCount(0, Contact::all());

        $data = ['dealership_id' => $dealership->id];
        $route = route('admin.leads.derive', $lead);
        $this->put($route, $data);
        $lead = Lead::find($lead->id);
        $this->assertCount(1, Contact::all());
        $this->assertEquals(Contact::first()->id, $lead->contact_id);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_lead_is_asociated_to_contact_if_it_exists_when_derived()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $lead = factory(Lead::class)->create([
            'dealership_id' => null,
            'status' => LeadStatus::Creado,
            'user_id' => null,
        ]);
        $dealership = factory(Dealership::class)->create();
        $contact = factory(Contact::class)->create([
            'email' => $lead->email,
            'dealership_id' => $dealership->id,
        ]);
        factory(User::class, 2)->state('owner')->create([
            'dealership_id' => $dealership->id,
        ]);
        $this->assertCount(1, Contact::all());

        $data = ['dealership_id' => $dealership->id];
        $route = route('admin.leads.derive', $lead);
        $this->put($route, $data);
        $lead = Lead::find($lead->id);
        $this->assertCount(1, Contact::all());
        $this->assertEquals($contact->id, $lead->contact_id);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_create_a_contact()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $contact_data = factory(Contact::class)->make();
        $data = [
            'name' => $contact_data->name,
            'email' => $contact_data->email,
            'phone' => $contact_data->phone,
            'phone2' => $contact_data->phone2,
            'address' => $contact_data->address,
            'geo_loc1' => $contact_data->geo_loc1,
            'geo_loc2' => $contact_data->geo_loc2,
            'geo_loc3' => $contact_data->geo_loc3,
            'type' => $contact_data->type,
            'description' => $contact_data->description,
            'dealership_id' => $contact_data->dealership_id,
        ];
        $response = $this->post('/contacts', $data);
        $response->assertRedirect();
        $this->assertCount(1, Contact::all());
        $created_contact = Contact::first();
        $this->assertFalse($created_contact->is_subscribed);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_edit_a_contact()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $contact = factory(Contact::class)->create(['name' => 'Pedreo Perez']);
        $data = [
            'name' => 'Pedro2',
            'email' => $contact->email,
            'phone' => $contact->phone,
            'phone2' => $contact->phone2,
            'address' => $contact->address,
            'geo_loc1' => $contact->geo_loc1,
            'geo_loc2' => $contact->geo_loc2,
            'geo_loc3' => $contact->geo_loc3,
            'type' => $contact->type,
            'description' => $contact->description,
            'is_subscribed' => $contact->is_subscribed,
        ];
        $response = $this->put(route('contacts.update', $contact->id), $data);
        $response->assertRedirect();
        $updated_contact = Contact::where('email', $contact->email)->first();
        $this->assertEquals('Pedro2', $updated_contact->name);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_validates_unique_email()
    {
        $this->loggedInAdmin();
        $dealership = factory(Dealership::class)->create();
        $existing_contact = factory(Contact::class)->create([
            'email' => 'pedro2@email.com',
            'dealership_id' => $dealership->id,
        ]);
        $contact = factory(Contact::class)->create(['dealership_id' => $existing_contact->dealership_id]);
        $data = [
            'email' => 'pedro2@email.com',
        ];
        $response = $this->put(route('contacts.update', $contact->id), $data);
        $response->assertRedirect();
        $response->assertSessionHas('notification', [
            'type' => 'error',
            'message' => 'Ya existe un contacto con ese email',
            'title' => null,
        ]);
    }

    /**
     * @return void
     *
     * @test
     */
    public function an_admin_can_delete_a_contact()
    {
        $this->withoutExceptionHandling();
        $this->loggedInAdmin();
        $contact = factory(Contact::class)->create();
        $first_lead = factory(Lead::class)->create(['contact_id' => $contact->id]);
        $second_lead = factory(Lead::class)->create(['contact_id' => $contact->id]);
        $this->assertCount(2, $contact->leads);
        $this->assertEquals($first_lead->contact_id, $contact->id);
        $this->assertEquals($second_lead->contact_id, $contact->id);
        $route = route('contacts.destroy', $contact);
        $response = $this->delete($route);
        $response->assertRedirect(route('contacts'));
        $this->assertCount(0, Contact::all());

        $first_lead = Lead::find($first_lead->id);
        $second_lead = Lead::find($second_lead->id);
        $this->assertEquals(null, $first_lead->contact_id);
        $this->assertEquals(null, $second_lead->contact_id);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_authorize_show_contact()
    {
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->create(['dealership_id' => $dealership->id]);
        $dealership2 = factory(Dealership::class)->create();
        $contact2 = factory(Contact::class)->create(['dealership_id' => $dealership2->id]);

        $this->actingAs($owner);
        $response = $this->get(route('contacts.show', $contact2));
        $response->assertStatus(403);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_authorize_update_contact()
    {
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->create(['dealership_id' => $dealership->id]);
        $dealership2 = factory(Dealership::class)->create();
        $contact2 = factory(Contact::class)->create(['dealership_id' => $dealership2->id]);

        $this->actingAs($owner);
        $response = $this->put(route('contacts.update', $contact2), ['email' => 'nuevo@example.cp']);
        $response->assertStatus(403);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_authorize_delete_contact()
    {
        $dealership = factory(Dealership::class)->create();
        $owner = factory(User::class)->create(['dealership_id' => $dealership->id]);
        $dealership2 = factory(Dealership::class)->create();
        $contact2 = factory(Contact::class)->create(['dealership_id' => $dealership2->id]);

        $this->actingAs($owner);
        $response = $this->delete(route('contacts.destroy', $contact2));
        $response->assertStatus(403);
    }

    /**
     * @return void
     *
     * @test
     */
    public function it_becomes_client_when_lead_is_won()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $seller = factory(User::class)->state('seller')->create(['dealership_id' => $dealership->id]);
        $this->actingAs($seller);
        $contact = factory(Contact::class)->create([
            'dealership_id' => $dealership->id,
            'type' => ContactTypes::Prospecto,
        ]);
        $lead = factory(Lead::class)->create([
            'dealership_id' => $dealership->id,
            'contact_id' => $contact->id,
        ]);
        $data = [
            'type' => ActivityTypes::Venta,
            'lead_id' => $lead->id,
            'observation' => 'una observacion cualquiera',
            'folios' => [],
        ];

        $store_route = route('leads.activities.store', $lead);
        $response = $this->post($store_route, $data);
        $response->assertRedirect();
        $contact = Contact::find($contact->id);
        $this->assertEquals(ContactTypes::Cliente, $contact->type);
    }

    /**
     * @return void
     *
     * @test
     */
    public function a_seller_can_create_a_lead_associated_to_dealer()
    {
        $this->withoutExceptionHandling();
        $dealership = factory(Dealership::class)->create();
        $this->loggedInSeller(null, $dealership);
        $contact_data = factory(Contact::class)->make();
        $data = [
            'name' => $contact_data->name,
            'email' => $contact_data->email,
            'phone' => $contact_data->phone,
            'phone2' => $contact_data->phone2,
            'address' => $contact_data->address,
            'geo_loc1' => $contact_data->geo_loc1,
            'geo_loc2' => $contact_data->geo_loc2,
            'geo_loc3' => $contact_data->geo_loc3,
            'type' => $contact_data->type,
            'description' => $contact_data->description,
            'is_subscribed' => $contact_data->is_subscribed,
        ];
        $response = $this->post('/contacts', $data);
        $response->assertRedirect();
        $this->assertCount(1, Contact::all());
        $created_contact = Contact::first();
        $this->assertEquals($created_contact->dealership_id, $dealership->id);
    }
}
