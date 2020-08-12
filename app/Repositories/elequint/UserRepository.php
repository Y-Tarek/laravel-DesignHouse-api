<?php

namespace App\Repositories\elequint;
use App\Repositories\elequint\BaseRepository;
use App\Models\User;
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

   
 }