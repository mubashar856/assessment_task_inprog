<?php
/**
 * Created by PhpStorm.
 * User: mubashar.hassan
 * Date: 11/1/2019
 * Time: 9:52 PM
 */

namespace App\Repositories;


use App\Models\Match;

class MatchRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Match();
    }

    function first ($matchId) {
        return $this->model->where('id', $matchId)->first();
    }

    function get ($filter) {
        $matches = $this->model->query();
        if (isset($filter['tournament_id'])) {
            $matches = $matches->where('tournament_id', $filter['tournament_id']);
        }
        return $matches->get();
    }

    function update ($matchId, $data) {
        $match = $this->model->where('id', $matchId)->first();
        if ($match) {
            if (isset($data['status'])) {
                $match->status = $data['status'];
            }
            return $match->save();
        }
        return false;
    }
}