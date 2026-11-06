<?php

namespace App\Providers;

use App\Contact;
use App\Dealership;
use App\Lead;
use App\Policies\ContactPolicy;
use App\Policies\DealershipPolicy;
use App\Policies\LeadPolicy;
use App\Policies\MediaPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\Models\Media;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Lead::class => LeadPolicy::class,
        User::class => UserPolicy::class,
        Media::class => MediaPolicy::class,
        Contact::class => ContactPolicy::class,
        Dealership::class => DealershipPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Admin policies
        foreach (get_class_methods(new \App\Policies\AdminPolicy) as $method) {
            Gate::define($method, "App\Policies\AdminPolicy@{$method}");
        }
        // Kam policies. Como no hay modelo kam,
        // toca hacer esta trampa para usar los
        // metodos de las policies y no de las Gates
        foreach (get_class_methods(new \App\Policies\KamPolicy) as $method) {
            Gate::define($method, "App\Policies\KamPolicy@{$method}");
        }
    }
}
