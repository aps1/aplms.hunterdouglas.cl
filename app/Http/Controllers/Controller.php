<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\Notifications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, Notifications, ValidatesRequests;

    /**
     * Consigue el rango de fecha por defecto
     */
    public function getDateRangeFromRequest(Request $request): array
    {
        $from = $request->get('from') ?
            Carbon::createFromFormat('d-m-Y', $request->get('from'))->startOfDay() :
            Carbon::now()->subDays(90)->startOfDay();
        $to = $request->get('to') ?
            Carbon::createFromFormat('d-m-Y', $request->get('to'))->endOfDay() :
            Carbon::now()->endOfDay();

        return ['from' => $from, 'to' => $to];
    }
}
