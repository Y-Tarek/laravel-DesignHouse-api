<?php

namespace App\Repositories\elequint;
use App\Exceptions\ModelNotDefined;
use App\Repositories\Contracts\IBase;

 abstract class BaseRepository implements IBase
 {
   protected $model;
   public function __construct()
   {
       $this->model = $this->getClassModel();
   }

   public function all()
   {
      return $this->model->all();
   }

   protected function getClassModel()
   {
          if(! method_exists($this,'model'))
          {
              throw new ModelNotDefined();
          }
          return app()->make($this->model());
   }
 }