<?php

namespace App\Services;

use App\Lead;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class WcpLeadService
{
    protected $lead;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function syncLeadWithWcp()
    {
        if (! $this->leadIsForWcp()) {
            return;
        }
        $unified_lead_id = $this->storeLeadInUnifiedDB();
        $this->notifyWcpLms($unified_lead_id);
    }

    public function storeLeadInUnifiedDB()
    {
        $data = $this->parseLeadData();
        $id = DB::connection('unified')
            ->table('web_latam_sml.unificada')
            ->insertGetId($data);

        $id_with_zeros = str_pad($id, 8, '0', STR_PAD_LEFT);
        $web_identifier = $this->getLeadCountry().$id_with_zeros;
        DB::connection('unified')
            ->table('web_latam_sml.unificada')
            ->where('id', $id)
            ->update(['web_identifier' => $web_identifier]);

        return $id;
    }

    public function notifyWcpLms($unified_lead_id)
    {
        $client = new Client;
        $response = $client->request('POST', 'https://lmshdlao.com/api/leads', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJjb3RpemFjaW9uIiwibmFtZSI6ImVsIGNvbXBpIiwiaWF0IjoxNTE2MjM5MDIyfQ.vZx2EGuHxCJ2mYfUd9o1EnoDhTYzKgsMd-HJoDJEw2w',
            ],
        ]);
    }

    public function leadIsForWcp(): bool
    {
        return $this->leadIsForWcpCountries() && $this->leadHasWcpWords();
    }

    /**
     * Acomoda la data del lead segun lo que necesita la unificada
     */
    protected function parseLeadData(): array
    {
        return [
            'form_id' => 'lms-ap-cl',
            'brand' => 'wcp',
            'web_identifier' => '',
            'name' => $this->lead->name,
            'email' => $this->lead->email,
            'phone' => $this->lead->phone,
            'country' => strtolower($this->getLeadCountry()),
            'address' => $this->lead->address,
            'geo_loc1' => $this->lead->geo_loc1,
            'geo_loc2' => $this->lead->geo_loc2,
            'geo_loc3' => $this->lead->geo_loc3,
            'business' => null,
            'occupation' => null,
            'date' => $this->lead->date,
            'products' => $this->lead->product,
            'dealer_id' => null,
            'dealer_name' => null,
            'page_title' => $this->lead->page_title,
            'is_subscribed' => $this->lead->is_subscribed,
            'message' => $this->lead->message,
            'dynamic_data' => null,

        ];
    }

    protected function leadIsForWcpCountries(): bool
    {
        $wcp_countries = [
            'cl',
            'chile',
            'ar',
            'argentina',
            'mx',
            'mexico',
        ];
        $lead_country = strtolower($this->lead->country);

        return in_array($lead_country, $wcp_countries);
    }

    protected function getLeadCountry()
    {
        $lead_country = strtolower($this->lead->country);
        if (in_array($lead_country, ['cl', 'chile'])) {
            return 'CL';
        }
        if (in_array($lead_country, ['ar', 'argentina'])) {
            return 'AR';
        }
        if (in_array($lead_country, ['mx', 'mexico'])) {
            return 'MX';
        }

        return null;
    }

    protected function leadHasWcpWords(): bool
    {
        $wcp_words = [
            'cortinas',
            'cortina',
            'toldos',
            'toldo',
            'persianas',
            'persiana',
            'roller',
            'rollers',
            'cortina roller',
            'cortinas roller',
            'cortina roller quantum',
            'cortina twinline',
            'cortina pirouette',
            'cortina luminette',
            'cortina duette',
            'cortina silhouette',
            'panel skyline',
            'persiana precious metals',
            'persiana country woods',
            'persiana de exterior p72',
            'toldo vertical',
            'toldo proyectante',
            'toldo horizontal',
            'toldo pivotante',
            'motorización powerview',
        ];
        foreach ($wcp_words as $word) {
            if (strpos($this->lead->message, $word)) {
                return true;
            }
        }

        return false;
    }
}
