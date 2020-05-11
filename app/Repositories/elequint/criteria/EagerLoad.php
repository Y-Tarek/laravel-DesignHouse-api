<?php
namespace App\Repositories\elequint\criteria;

use App\Repositories\Criteria\ICriterion;

class EagerLoad implements ICriterion
{
  protected $relationship;
  public function __construct($relationship)
  {
      $this->relationship = $relationship;
  }

    public function apply($model)
    {
        return $model->with($this->relationship);
    }
}