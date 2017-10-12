<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Entities\UserArchive;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveUserArchive
{
    
    protected $model;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserArchive $model)
    {
        $this->model = $model;
    }

    /**
     * Handle the event.
     *
     * @param  UserUpdated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        //store archive data
        if ($event->user) {
            
            //create user archive
            $user = $this->model->create($event->user->toArray());

            return $user;

        }
    }
}
