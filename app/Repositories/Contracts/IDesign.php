<?php

 namespace App\Repositories\Contracts;
 
 interface IDesign
 {
   public function applyTags($id, array $data);
 }