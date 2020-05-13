<?php

 namespace App\Repositories\Contracts;
 
 interface IDesign
 {
   public function applyTags($id, array $data);
   public function addComments($design_id, array $data);
   public function like($id);
   public function isLikedByUser($design_id);
 }