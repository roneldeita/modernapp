<?php

namespace App\Listeners;

use App\Events\PostEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Post;

class PostEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostEvent  $event
     * @return void
     */
    public function handle(PostEvent $event)
    {
        
        //return  $event;

    }
}
