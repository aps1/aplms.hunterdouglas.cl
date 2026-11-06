<?php

namespace App\Http\Controllers;

use App\Folio;
use App\Lead;
use Illuminate\Http\Request;

class LeadsFoliosController extends Controller
{
    /**
     * @return void
     */
    public function update(Lead $lead, Request $request)
    {
        // Elimina los folios del lead
        $lead->folios()->delete();
        // el formulario puede tener folios nulos por el dise;o
        foreach ($request->get('folios') as $folio_number) {
            if (! $folio_number) {
                continue;
            }
            Folio::create([
                'number' => $folio_number,
                'lead_id' => $lead->id,
            ]);
        }
        $this->notifySuccess('Se editaron los folios', 'Todo bien');

        return redirect()->back();
    }
}
