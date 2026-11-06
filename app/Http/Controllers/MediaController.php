<?php

namespace App\Http\Controllers;

use App\Events\LeadDerivado;
use App\Lead;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;

class MediaController extends Controller
{
    /**
     * @return void
     */
    public function download(Media $media, Request $request)
    {
        $this->authorize('view', $media);

        return $media;
    }

    public function test(Request $request)
    {
        $lead = Lead::find(2);
        event(new LeadDerivado($lead));

        return $lead;
    }
}
