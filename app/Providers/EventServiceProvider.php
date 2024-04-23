<?php

namespace App\Providers;

use App\Events\CatalogActivation;
use App\Events\TopicRequestProcessed;
use App\Events\UniversityProcessed;
use App\Events\UserRegistered;
use App\Listeners\SendCatalogActivationEmail;
use App\Listeners\SendTopicRequestProcessedEmail;
use App\Listeners\SendUniversityProcessedEmail;
use App\Listeners\SendUserRegisteredEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        CatalogActivation::class => [
            SendCatalogActivationEmail::class,
        ],
        TopicRequestProcessed::class => [
            SendTopicRequestProcessedEmail::class,
        ],
        UniversityProcessed::class => [
            SendUniversityProcessedEmail::class,
        ],
        UserRegistered::class => [
            SendUserRegisteredEmail::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
