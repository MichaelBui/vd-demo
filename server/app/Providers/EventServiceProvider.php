<?php

namespace App\Providers;

use App\Events\ObjectSavedEvent;
use App\Listeners\GenerateObjectLogListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        ObjectSavedEvent::class => [
            GenerateObjectLogListener::class,
        ],
    ];
}
