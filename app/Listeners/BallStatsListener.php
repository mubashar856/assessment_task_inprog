<?php

namespace App\Listeners;

use App\Events\BallStatsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BallStatsListener
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
     * @param  BallStatsEvent  $event
     * @return void
     */
    public function handle(BallStatsEvent $event)
    {
        //
    }
}
