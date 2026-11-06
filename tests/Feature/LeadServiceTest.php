<?php

namespace Tests\Feature;

use App\Events\WebLeadRegistered;
use App\Lead;
use App\WebClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LeadServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_stores_leads_from_web_form()
    {
        Event::fake([WebLeadRegistered::class]);
        $this->withoutExceptionHandling();
        $lead_data = factory(Lead::class)->make();
        $data = [
            'name' => $lead_data->name,
            'email' => $lead_data->email,
            'phone' => $lead_data->phone,
            'country' => $lead_data->country,
            'product_category' => 'Productos Interiores',
            'origin' => 'Cotización',
            'message' => 'Un mensaje cualquiera',
        ];
        $client = factory(WebClient::class)->create();

        $this->withHeader('Authorization', 'Bearer '.$client->token)
            ->post('api/leads', $data);
        $this->assertCount(1, Lead::all());
        Event::assertDispatched(WebLeadRegistered::class);
    }

    /**
     * @test
     */
    public function it_stores_leads_from_cliengo()
    {
        $this->withoutExceptionHandling();
        $data = $this->getCliengoDataExample();
        $client = factory(WebClient::class)->create([
            'name' => 'cliengo',
            'token' => '123456678',
        ]);

        $response = $this->post('api/leads', $data);
        $this->assertCount(1, Lead::all());
    }

    /**
     * @test
     */
    public function it_validates_token()
    {
        $this->withoutExceptionHandling();
        $lead_data = factory(Lead::class)->make();
        $data = [
            'name' => $lead_data->name,
            'email' => $lead_data->email,
            'phone' => $lead_data->phone,
            'country' => $lead_data->country,
            'product_category' => 'Productos Interiores',
            'origin' => 'Cotización',
            'message' => 'Un mensaje cualquiera',
        ];
        $client = factory(WebClient::class)->create(['token' => '123345']);

        $response = $this->withHeader('Authorization', 'Bearer '.'asdkfjh')
            ->post('api/leads', $data);
        $response->assertStatus(500);
        $this->assertCount(0, Lead::all());
    }

    protected function getCliengoDataExample()
    {
        return (array) json_decode(
            '{
              "id": "5f47d3fef6d2d8002aea58ca",
              "accountId": "58d04d6be4b06a3ae1484e31",
              "websiteId": "5f46dbf0041ccd002affa528",
              "status": "new",
              "name": "tina",
              "email": "martinezislapame@gmail.com",
              "phone": null,
              "creationDate": "2020-08-27T15:40:46.592Z",
              "lastUpdateDate": "Aug 27, 2020 3:40:48 PM",
              "lastName": null,
              "age": 0,
              "webhookKey": "123456678",
              "ip": "190.100.219.58",
              "conversionUrl": "https://www.hunterdouglas.cl/ap/enews/sh/productos-3form-de-hunter-douglas-revestimien",
              "message": "cliente: Hola, se puede colocar en la tina del baño?\nasesor: Tina yo me encargo de ponerte en contacto con un asesor para que responda tu consulta\nasesor: podrías dejarme tu teléfono completo?\ncliente: Con que se puede pegar\nasesor: podrías dejarme tu email?\ncliente: Martinezislapame@gmail.com\nasesor: ¿Necesitas información técnica o una cotización?\ncliente: Información\nasesor: quieres dejarme alguna consulta adicional?",
              "geoip": {
                "latitude": "-33.4569",
                "longitude": "-70.6483",
                "country": "CL",
                "state": "Santiago Metropolitan",
                "city": "Santiago",
                "zipCode": "8320000"
              },
              "duplicatedContact": false,
              "notes": [],
              "logs": [],
              "calls": [],
              "medium": "none",
              "mediumTranslate": "Ninguno",
              "customParams": {
                "ldkCompanyId": "58d04d6be4b06a3ae1484e31",
                "ldkWebsiteId": "5f46dbf0041ccd002affa528",
                "tags[1]": "posted_name",
                "tags[0]": "fired_new_lead",
                "conversationId": "5f47d2b44eb8c5a1a73759db",
                "tags[2]": "posted_email",
                "channel": "CHATBOT",
                "question_1": "Información",
                "body": "question_1=Informaci%C3%B3n&phoneVerified=&contactDni=&ldkCompanyId=58d04d6be4b06a3ae1484e31&ldkWebsiteId=5f46dbf0041ccd002affa528&ldkUtms=&conversationId=5f47d2b44eb8c5a1a73759db&chatSessionId=5f47d2b44eb8c5a1a73759db&channel=CHATBOT&chatUrl=https%3A%2F%2Fwww.hunterdouglas.cl%2Fap%2Fenews%2Fsh%2Fproductos-3form-de-hunter-douglas-revestimien&chatIp=190.100.219.58&chatLog=cliente%3A%20Hola%2C%20se%20puede%20colocar%20en%20la%20tina%20del%20ba%C3%B1o%3F%0Aasesor%3A%20Tina%20yo%20me%20encargo%20de%20ponerte%20en%20contacto%20con%20un%20asesor%20para%20que%20responda%20tu%20consulta%0Aasesor%3A%20podr%C3%ADas%20dejarme%20tu%20tel%C3%A9fono%20completo%3F%0Acliente%3A%20Con%20que%20se%20puede%20pegar%0Aasesor%3A%20podr%C3%ADas%20dejarme%20tu%20email%3F%0Acliente%3A%20Martinezislapame%40gmail.com%0Aasesor%3A%20%C2%BFNecesitas%20informaci%C3%B3n%20t%C3%A9cnica%20o%20una%20cotizaci%C3%B3n%3F%0Acliente%3A%20Informaci%C3%B3n%0Aasesor%3A%20quieres%20dejarme%20alguna%20consulta%20adicional%3F%0A&chatName=tina&chatEmail=martinezislapame%40gmail.com&chatPhone=&chatInternationalPhone=&chatIdNumber=&tags%5B0%5D=fired_new_lead&tags%5B1%5D=posted_name&tags%5B2%5D=posted_email"
              },
              "rating": 0,
              "conversationId": "5f47d2b44eb8c5a1a73759db",
              "utmMedium": "none",
              "utmCampaign": null,
              "utmContent": null,
              "utmTerm": null,
              "accountName": "Administrador",
              "websiteName": "https://www.hunterdouglas.cl/ap/",
              "entryMethod": "CHATBOT",
              "tags": [
                "fired_new_lead",
                "posted_email",
                "posted_name"
              ],
              "intents": [
                "otro"
              ],
              "entities": [
                {
                  "slot_id": "email",
                  "values": [
                    "Martinezislapame@gmail.com"
                  ]
                }
              ],
              "extraParams": {
                "ldkCompanyId": "58d04d6be4b06a3ae1484e31",
                "ldkWebsiteId": "5f46dbf0041ccd002affa528",
                "tags[1]": "posted_name",
                "tags[0]": "fired_new_lead",
                "conversationId": "5f47d2b44eb8c5a1a73759db",
                "tags[2]": "posted_email",
                "channel": "CHATBOT",
                "question_1": "Información",
                "body": "question_1=Informaci%C3%B3n&phoneVerified=&contactDni=&ldkCompanyId=58d04d6be4b06a3ae1484e31&ldkWebsiteId=5f46dbf0041ccd002affa528&ldkUtms=&conversationId=5f47d2b44eb8c5a1a73759db&chatSessionId=5f47d2b44eb8c5a1a73759db&channel=CHATBOT&chatUrl=https%3A%2F%2Fwww.hunterdouglas.cl%2Fap%2Fenews%2Fsh%2Fproductos-3form-de-hunter-douglas-revestimien&chatIp=190.100.219.58&chatLog=cliente%3A%20Hola%2C%20se%20puede%20colocar%20en%20la%20tina%20del%20ba%C3%B1o%3F%0Aasesor%3A%20Tina%20yo%20me%20encargo%20de%20ponerte%20en%20contacto%20con%20un%20asesor%20para%20que%20responda%20tu%20consulta%0Aasesor%3A%20podr%C3%ADas%20dejarme%20tu%20tel%C3%A9fono%20completo%3F%0Acliente%3A%20Con%20que%20se%20puede%20pegar%0Aasesor%3A%20podr%C3%ADas%20dejarme%20tu%20email%3F%0Acliente%3A%20Martinezislapame%40gmail.com%0Aasesor%3A%20%C2%BFNecesitas%20informaci%C3%B3n%20t%C3%A9cnica%20o%20una%20cotizaci%C3%B3n%3F%0Acliente%3A%20Informaci%C3%B3n%0Aasesor%3A%20quieres%20dejarme%20alguna%20consulta%20adicional%3F%0A&chatName=tina&chatEmail=martinezislapame%40gmail.com&chatPhone=&chatInternationalPhone=&chatIdNumber=&tags%5B0%5D=fired_new_lead&tags%5B1%5D=posted_name&tags%5B2%5D=posted_email"
              }
            }'
        );
    }
}
