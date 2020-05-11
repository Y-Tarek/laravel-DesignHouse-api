<?php

namespace App\Repositories\elequint;
use Illuminate\Support\Arr;
use App\Exceptions\ModelNotDefined;
use App\Repositories\Contracts\IBase;
use App\Repositories\Criteria\ICriteria;

 abstract class BaseRepository implements IBase,ICriteria
 {
   protected $model;
   public function __construct()
   {
       $this->model = $this->getClassModel();
   }



      public function all()
      {
          return $this->model->get();
      }

      public function find($id)
      {
        return $this->model->findOrFail($id);
      }

      public function findWhere($col,$val)
      {
         return $this->model->where($col,$val)->get();
      }
      
      public function findWhereFirst($col,$val)
      {
        return $this->model->where($col,$val)->firstOrFail();
      }

      public function create(array $data)
      {
        return $this->model->create($data);
      }

      public function update($id, array $data)
      {
         $record = $this->find($id);
         $record->update($data);
          return $record;
      }

      public function delete($id)
      {
        $record = $this->find($id);
        return $record->delete();
      }

      public function paginate($perPage = 10)
      {
           return $this->model->paginate($perPage);
      }

//Returning a new version of a model based on the criteria and add it to whatever method in that repository class
      public function withCriteria(... $criteria) 
      {
        $criteria = Arr::flatten($criteria);
        foreach($criteria as $criterion){
          $this->model = $criterion->apply($this->model);
        }
        return $this; 
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