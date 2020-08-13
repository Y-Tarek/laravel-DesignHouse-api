<?php
namespace App\Repositories\elequint\criteria;

use App\Repositories\Criteria\ICriterion;

class WithTrashed implements ICriterion
{
    public function apply($model)
    {
        return $model->withTrashed();
    }
}