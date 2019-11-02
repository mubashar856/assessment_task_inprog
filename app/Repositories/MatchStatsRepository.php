<?php
/**
 * Created by PhpStorm.
 * User: mubashar.hassan
 * Date: 11/2/2019
 * Time: 12:17 AM
 */

namespace App\Repositories;


use App\Models\MatchStats;

class MatchStatsRepository extends Repository
{
    public function __construct()
    {
        $this->model = new MatchStats();
    }

    function add ($matchId, $winningTeamId, $team1Score, $team1Wickets, $team1Overs, $team2Score, $team2Wickets, $team2Overs) {
        $stats = new $this->model();
        $stats->match_id = $matchId;
        $stats->winning_team_id = $winningTeamId;
        $stats->team1_score = $team1Score;
        $stats->team1_wickets = $team1Wickets;
        $stats->team1_overs = $team1Overs;
        $stats->team2_score = $team2Score;
        $stats->team2_wickets = $team2Wickets;
        $stats->team2_overs = $team2Overs;
        return $stats->save();
    }
}