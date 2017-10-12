<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        
        'App\Events\UserCreated' => [
            'App\Listeners\SendConfirmationEmail',
            'App\Listeners\SendConfirmationSms',
            'App\Listeners\SaveUserArchive',
        ],
        'App\Events\UserUpdated' => [
            'App\Listeners\SaveUserUpdatedArchive',
        ],
        'App\Events\MessageCreated' => [
            'App\Listeners\SendReceivedMessageEmail'
        ],
        'App\Events\ChatThreadRead' => [
            'App\Listeners\SaveChatThreadReadState'
        ],
        'App\Events\ChatMessageCreated' => [
            'App\Listeners\SendChatMessageCreatedEmail'
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
        //
    }
}
