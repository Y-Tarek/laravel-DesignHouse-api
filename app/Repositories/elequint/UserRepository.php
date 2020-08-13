<?php

namespace App\Repositories\elequint;
use App\Models\User;
use Illuminate\Http\Request;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use App\Repositories\elequint\BaseRepository;
 use App\Repositories\Contracts\IUser;

 class UserRepository extends BaseRepository implements IUser
 {
   public function model()
   {
       return User::class;//App\Models\User
   }
    
   public function findByEmail($email)
   {
     return $this->model
                 ->where('email',$email)
                 ->first();
   }


   public function search(Request $req)
   {
     $query = (new $this->model)->newQuery();

      if($req->has_designs){
        $query->has('designs');
      }

      if($req->available_to_hire){
        $query->where('available_to_hire', true);
      }

      $lat = $req->latitude;
      $lng = $req->longtitude;
      $dist = $req->distance;
      $unit = $req->unut;

       if($lat && $lng){
         $point = new Point($lat, $lng);
         $unit == 'km' ? $dist *=1000 : $dist *=1609.34;
         $query->distanceSphereExcludingSelf('location',$point,$dist);
       }

       if($req->orderBY == 'closest'){
         $query->orderByDistanceSphere('location',$point,'asc');
       }
       else if($req->orderBy =='Latest'){
         $query->latest();
       }
       else{
         $query->oldest();
       }

      return  $query->get();
     
   }
   
 }