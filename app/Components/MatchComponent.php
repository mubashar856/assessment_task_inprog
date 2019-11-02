<?php
/**
 * Created by PhpStorm.
 * User: mubashar.hassan
 * Date: 11/1/2019
 * Time: 9:13 PM
 */

namespace App\Components;


use App\Enums\ScoreName;
use App\Events\MatchStatsEvent;
use App\Repositories\MatchHistoryRepository;
use App\Repositories\MatchRepository;
use App\Repositories\MatchStatsRepository;
use App\Repositories\OverRepository;

class MatchComponent
{
    private $overRepository;
    private $matchHistoryRepository;
    private $matchRepository;
    private $matchStatsRepository;

    public function __construct()
    {
        $this->overRepository = new OverRepository();
        $this->matchHistoryRepository = new MatchHistoryRepository();
        $this->matchRepository = new MatchRepository();
        $this->matchStatsRepository = new MatchStatsRepository();
    }

    function startOver($matchId, $bowlerId)
    {
        return $this->overRepository->add($matchId, $bowlerId);
    }

    function addBall($matchId, $overId, $bowlerId, $batsmanId, $ballNumber, $scoreTypeId, $extraScoreTypeId, $score, $remarks = '')
    {
        return $this->matchHistoryRepository->add($matchId, $overId, $bowlerId, $batsmanId, $ballNumber, $scoreTypeId, $extraScoreTypeId, $score, $remarks);
    }

    function getTournamentMatches($tournamentId)
    {
        return $this->matchRepository->get(['tournamentId' => $tournamentId]);
    }

    function updateMatchStatus($matchId, $status)
    {
        return $this->matchRepository->update($matchId, ['status' => $status]);
    }

    function storeMatchStats($matchId)
    {
        $match = $this->matchRepository->first($matchId);
        $team1Stats = $this->getTeamStats($matchId, $match->team1);
        $team2Stats = $this->getTeamStats($matchId, $match->team2);
        $winningTeamId = $team1Stats['score'] > $team2Stats['score'] ? $team1Stats['id'] : $team2Stats['id'];
        event(new MatchStatsEvent($match->id, $team1Stats['score'], $team1Stats['wickets'], $team1Stats['overs'], $team2Stats['score'], $team2Stats['wickets'], $team2Stats['overs']));
        return $this->matchStatsRepository->add($match->id, $winningTeamId, $team1Stats['score'], $team1Stats['wickets'], $team1Stats['overs'], $team2Stats['score'], $team2Stats['wickets'], $team2Stats['overs']);
    }

    function getTeamStats($matchId, $team)
    {
        $playerIds = array_map(function ($player) {
            return $player['id'];
        }, $team->players->toArray());
        $matchStats = $this->matchHistoryRepository->get(['matchId' => $matchId, 'target' => 'batsman', 'playerIds' => $playerIds]);
        $score = $matchStats->sum('score');
        $balls = $matchStats->whereNotIn('score_type_id', [ScoreName::$NO_BALL_ID, ScoreName::$WIDE_BALL_ID])->count();
        $overs = $this->calculateOvers($balls);
        $wickets = $matchStats->whereIn('score_type_id', [ScoreName::$CATCH_OUT_ID, ScoreName::$RUN_OUT_ID, ScoreName::$OUT_ID])->count();
        return ['id' => $team->id, 'score' => $score, 'overs' => $overs, 'wickets' => $wickets];
    }

    private function calculateOvers($balls)
    {
        $first = floor($balls / 6);
        $second = fmod($balls, 6) / 10;
        return $first + $second;
    }
}