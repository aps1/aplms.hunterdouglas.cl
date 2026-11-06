<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\WebLeadRegistered::class => [
            \App\Listeners\SyncLeadWithWcp::class,
        ],
        \App\Events\ManualLeadRegistered::class => [],
        \App\Events\LeadDerivado::class => [
            \App\Listeners\NotifyLeadDerivado::class,
        ],
        \App\Events\LeadAsignado::class => [
            \App\Listeners\NotifyLeadAsignado::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
