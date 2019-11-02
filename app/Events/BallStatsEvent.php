<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BallStatsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $matchId;
    public $bowler;
    public $batsman;
    public $scoreType;
    public $score;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($matchId, $bowler, $batsman, $scoreType, $score)
    {
        $this->matchId = $matchId;
        $this->bowler = $bowler;
        $this->batsman = $batsman;
        $this->scoreType = $scoreType;
        $this->score = $score;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('cric-stats');
    }
}
