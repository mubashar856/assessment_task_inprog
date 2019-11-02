<?php
/**
 * Created by PhpStorm.
 * User: mubashar.hassan
 * Date: 11/1/2019
 * Time: 10:32 PM
 */

namespace App\Components;


use App\Repositories\ScoreTypeRepository;

class ScoreTypeComponent
{
    private $scoreTypeRepository;
    public function __construct()
    {
        $this->scoreTypeRepository = new ScoreTypeRepository();
    }

    function getScoreTypes () {
        return $this->scoreTypeRepository->get();
    }
}