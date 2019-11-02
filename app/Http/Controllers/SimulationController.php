<?php
/**
 * Created by PhpStorm.
 * User: mubashar.hassan
 * Date: 11/1/2019
 * Time: 9:49 PM
 */

namespace App\Http\Controllers;


use App\Components\MatchComponent;
use App\Components\ScoreTypeComponent;
use App\Enums\MatchStatus;
use App\Enums\ScoreName;
use App\Events\BallStatsEvent;
use App\Events\MatchStartEvent;
use App\Events\MatchStatsEvent;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    private $matchComponent;
    private $scoreTypeComponent;
    private $ballsPerOver = 6;
    private $totalOvers = 5;

    public function __construct(MatchComponent $matchComponent, ScoreTypeComponent $scoreTypeComponent)
    {
        $this->matchComponent = $matchComponent;
        $this->scoreTypeComponent = $scoreTypeComponent;
    }

    function startTournament(Request $request)
    {
        $tournamentId = $request['tournamentId'];
        $matches = $this->matchComponent->getTournamentMatches($tournamentId);
        foreach ($matches as $match) {
            event(new MatchStartEvent($match->id, $match->team1->name, $match->team2->name));
            $this->matchComponent->updateMatchStatus($match->id, MatchStatus::$IN_PROGRESS);
            $this->startInning($match->id, $match->team1, $match->team2);
            $this->startInning($match->id, $match->team2, $match->team1);
            $this->matchComponent->storeMatchStats($match->id);
            $this->matchComponent->updateMatchStatus($match->id, MatchStatus::$FINISHED);
        }
    }

    private function startInning($matchId, $battingTeam, $bowlingTeam)
    {
        $batsmen = $battingTeam->players->toArray();
        $bowlers = $bowlingTeam->players->toArray();
        $bowlerNumber = 0;
        $batsmanNumber = 1;
        $currentBatsmen = array_slice($batsmen, 0, 2);
        $facingBatsman = $currentBatsmen[0];
        for ($over = 1; $over <= $this->totalOvers; $over++) {
            $balls = [];
            $bowler = $this->getBowler($bowlerNumber, $bowlers);
            $overId = $this->matchComponent->startOver($matchId, $bowler['id']);
            $ballNumber = 0;
            while (count($balls) <= $this->ballsPerOver) {
                $ballNumber++;
                $ball = $this->ball();
                $score = $ball['score'];
                $score += $ball['name'] == ScoreName::$RUNNING ? rand(1, 3) : 0;
                event(new BallStatsEvent($matchId, $bowler['name'], $facingBatsman['name'], $ball['name'], $score));
                $this->matchComponent->addBall($matchId, $overId, $bowler['id'], $facingBatsman['id'], $ballNumber, $ball['id'], null, $score);
//                event(new MatchStatsEvent($facingBatsman['name']));
                if (!in_array($ball['name'], [ScoreName::$NO_BALL, ScoreName::$WIDE_BALL])) {
                    array_push($balls, 1);
                }
                if (in_array($ball['name'], [ScoreName::$CATCH_OUT, ScoreName::$RUN_OUT, ScoreName::$OUT])) {
                    if ($batsmanNumber == count($batsmen) - 1) {
                        return;
                    }
                    $currentBatsmen[0] = $batsmen[$batsmanNumber + 1];
                    $batsmanNumber++;
                }
                if ($ball['name'] != ScoreName::$WIDE_BALL && $score % 2 != 0) {
                    $currentBatsmen[0] = $currentBatsmen[1];
                    $currentBatsmen[1] = $facingBatsman;
                    $facingBatsman = $currentBatsmen[0];
                }
//                sleep(2);
            }
            $currentBatsmen[0] = $currentBatsmen[1];
            $currentBatsmen[1] = $facingBatsman;
            $facingBatsman = $currentBatsmen[0];
            $bowlerNumber++;
        }
    }

    private function ball()
    {
        $scoreTypes = $this->scoreTypeComponent->getScoreTypes()->toArray();
        return $scoreTypes[rand(0, count($scoreTypes) - 1)];
    }

    private function getBowler($bowlerNumber, $bowlers)
    {
        return $bowlerNumber < count($bowlers) ? $bowlers[$bowlerNumber + 1] : $bowlers[0];
    }

}