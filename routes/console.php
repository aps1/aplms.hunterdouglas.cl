<?php

use App\Lead;
use App\Repositories\ContactsRepository;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('contacts:associate', function () {
    $leads = Lead::with('dealership')->whereNotNull('dealership_id')->get();
    $contactRepo = new ContactsRepository;
    $count = $leads->count();
    $this->info("Procesando {$count} prospectos ...");
    $leads->each(function ($lead) use ($contactRepo) {
        $contactRepo->asociateContactFromLead($lead, $lead->dealership);
    });
})->describe('Crea y asocia los contactos de los leads');
