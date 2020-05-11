<?php
namespace App\Repositories\elequint\criteria;

use App\Repositories\Criteria\ICriterion;

class LatestFirst implements ICriterion
{
    public function apply($model)
    {
        return $model->latest();
    }
}