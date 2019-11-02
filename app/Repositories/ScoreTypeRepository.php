<?php
/**
 * Created by PhpStorm.
 * User: mubashar.hassan
 * Date: 11/1/2019
 * Time: 10:31 PM
 */

namespace App\Repositories;


use App\Models\ScoreType;

class ScoreTypeRepository extends Repository
{
    public function __construct()
    {
        $this->model = new ScoreType();
    }

    function get () {
        return $this->model->get();
    }
}