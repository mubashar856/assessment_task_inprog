<?php
/**
 * Created by PhpStorm.
 * User: mubashar.hassan
 * Date: 11/1/2019
 * Time: 9:35 PM
 */

namespace App\Repositories;


use App\Enums\BallType;
use App\Models\MatchHistory;

class MatchHistoryRepository extends Repository
{
    public function __construct()
    {
        $this->model = new MatchHistory();
    }

    function add ($matchId, $overId, $bowlerId, $batsmanId, $ballNumber, $scoreTypeId, $extraScoreTypeId, $score, $remarks = '') {
        $history = new $this->model();
        $history->match_id = $matchId;
        $history->over_id = $overId;
        $history->bowler_id = $bowlerId;
        $history->batsman_id = $batsmanId;
        $history->ball_number = $ballNumber;
        $history->score_type_id = $scoreTypeId;
        $history->is_extra_score = $extraScoreTypeId != null;
        if ($extraScoreTypeId != null) {
            $history->extra_score_type_id = $extraScoreTypeId;
        }
        $history->score = $score;
        $history->remarks = $remarks;
        $history->ball_type = BallType::$REGULAR;
        return $history->save();
    }

    function get ($filters) {
        $query = $this->model->query();
        if (isset($filters['matchId'])) {
            $query = $query->where('match_id', $filters['matchId']);
        }
        if (isset($filters['target'])) {
            if ($filters['target'] == 'batsman') {
                $query = $query->whereIn('batsman_id', $filters['playerIds']);
            }
        }
        return $query->get();
    }

}