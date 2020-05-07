<?php 
namespace App\Repositories\elequint;
use  App\Repositories\elequint\BaseRepository;
use App\Models\Design;
use App\Repositories\Contracts\IDesign;

class DesignRepository extends BaseRepository implements IDesign
{
   public function model()
   {
       return Design::class;//App\Models\Design
   }

}