<?php

namespace App\Listeners;

use App\Events\MatchStartEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MatchStartListener
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
     * @param  MatchStartEvent  $event
     * @return void
     */
    public function handle(MatchStartEvent $event)
    {
        //
    }
}
