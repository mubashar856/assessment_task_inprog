<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchStatsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $matchId;
    public $team1Score;
    public $team1Wickets;
    public $team1Overs;
    public $team2Score;
    public $team2Wickets;
    public $team2Overs;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($matchId, $team1Score, $team1Wickets, $team1Overs, $team2Score, $team2Wickets, $team2Overs)
    {
        $this->matchId = $matchId;
        $this->team1Score = $team1Score;
        $this->team1Wickets = $team1Wickets;
        $this->team1Overs = $team1Overs;
        $this->team2Score = $team2Score;
        $this->team2Wickets = $team2Wickets;
        $this->team2Overs = $team2Overs;
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
