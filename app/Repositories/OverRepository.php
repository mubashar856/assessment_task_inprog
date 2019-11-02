<?php
/**
 * Created by PhpStorm.
 * User: mubashar.hassan
 * Date: 11/1/2019
 * Time: 9:22 PM
 */

namespace App\Repositories;


use App\Enums\OverStatus;
use App\Models\Over;

class OverRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Over();
    }

    function add($matchId, $bowlerId)
    {
        $newOver = new $this->model();
        $newOver->match_id = $matchId;
        $newOver->bowler_id = $bowlerId;
        $newOver->status = OverStatus::$STARTED;
        $newOver->save();
        return $newOver->id;
    }
}