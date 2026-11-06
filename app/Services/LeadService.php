<?php
namespace App\Services;

use App\Enums\ActivityTypes;
use App\Enums\LeadOrigins;
use App\Enums\ProductCategory;
use App\Lead;
use App\WebClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadService {
    /**
     * undocumented function
     */
    public function storeLead(Request $request) {
        $client = $this->getWebServiceClient($request);

        if (! $client) {
            Log::error('Token invalido');
            //  print_r($client);
            //  die("Token invalido");
            return false;
        }
        $lead_data = $this->getLeadData($request, $client);

        $lead = Lead::create($lead_data);

        if ($lead) {
            // Registra actividad de creacion
            $activity_data_for_create = [
                'type'         => ActivityTypes::Creado,
                'observation'  => $lead->message,
                'localization' => $lead->localization,
            ];
            $lead->addActivity($activity_data_for_create);
        }

        return $lead;
    }

    /**
     * Parsea el producto
     *
     * @return void
     */
    protected function parseProduct($product) {
        switch ($product) {
        case 'Productos Interiores':
            $product = ProductCategory::Interiores;
            break;
        case 'Control Solar':
            $product = ProductCategory::Solar;
            break;
        case 'Fachadas':
            $product = ProductCategory::Fachadas;
            break;
        case 'Cubiertas-Tejuelas':
            $product = ProductCategory::Cubiertas;
            break;
        default:
            $product = ProductCategory::Otros;
            break;
        }

        return $product;
    }

    /**
     * El client registrado en base de datos con token.
     * por ahora es la web o cliengo
     */
    protected function getWebServiceClient(Request $request) {
        $token = $request->bearerToken();

        if (! $token && $request->has('webhookKey')) {
            $token = $request->get('webhookKey');
        }

        if (! $token) {
            $authHeader = $request->header('Authorization');
            if ($authHeader && preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        if (! $token) {
            return null;
        }

        return WebClient::where('token', $token)->first();
    }

    protected function getWebServiceClientDebug(Request $request) {
        Log::info('Cabecera Authorization: ' . $request->header('Authorization')); // Para depurar

        $token = $request->bearerToken(); // Usa el método de Laravel

        if (! $token) {
            Log::warning('No se encontró el token Bearer en la cabecera Authorization.');
            return null; // Importante retornar null si no hay token
        }

        Log::info('Token extraído: ' . $token);

        $client = WebClient::where('token', $token)->first();

        if (! $client) {
            Log::warning('No se encontró un WebClient con el token: ' . $token);
            return null; // Importante retornar null si no se encuentra el cliente
        }

        Log::info('WebClient encontrado: ' . $client->name);

        return $client;
    }

    /**
     * undocumented function
     */
    protected function getLeadData(Request $request, $client): array {
        if (@$client->name == 'cliengo') {
            return $this->getLeadDataFromCliengo($request);
        }

        return [
            'name'             => $request->get('name'),
            'email'            => $request->get('email'),
            'phone'            => $request->get('phone'),
            'country'          => $request->get('country') ?? 'CL',
            'geo_loc1'         => $request->input('state'),
            'geo_loc2'         => $request->input('geoip.city'),
            'product_category' => $this->parseProduct($request->get('product_category')),
            'origin'           => $request->get('origin'),
            'message'          => $request->get('message'),
            'localization'     => 'cl',
            'date'             => now(),
        ];
    }

    /**
     * undocumented function
     */
    protected function getLeadDataFromCliengo(Request $request): array {
        return [
            'name'             => $request->get('name') ?? 'Sin nombre',
            'email'            => $request->get('email'),
            'phone'            => $request->get('phone'),
            'country'          => $request->input('geoip.country') ?? 'CL',
            'geo_loc1'         => $request->input('geoip.state'),
            'geo_loc2'         => $request->input('geoip.city'),
            'product_category' => ProductCategory::Otros,
            'origin'           => LeadOrigins::Cliengo,
            'message'          => $request->get('message'),
            'localization'     => 'cl',
            'date'             => now(),
        ];
    }
}
