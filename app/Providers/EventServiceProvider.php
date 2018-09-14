<?php

namespace App\Providers;

use App\Listeners\Genesis\CreateExtraFields;
use App\Listeners\Genesis\CreateWorkflows;
use Betalabs\LaravelHelper\Events\GenesisCompleted;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        GenesisCompleted::class => [
            CreateWorkflows::class,
            CreateExtraFields::class,
            // You can add as much as you need...
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
