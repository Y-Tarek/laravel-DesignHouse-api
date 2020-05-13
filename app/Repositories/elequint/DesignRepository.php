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

   public function applyTags($id, array $data)
   {
       $design = $this->find($id);
      $design->retag($data);
   }

   public function addComments($design_id, array $data)
   {
       $design = $this->find($design_id);
       $comment = $design->comments()->create($data);
       return $comment;
   }
   public function like($id)
   {
       $design = $this->model->findOrFail($id);
       if($design->isLikedByUser(auth()->user()->id)){
           $design->unlike();
       }else{
           $design->like();
       }
   }

   public function isLikedByUser($design_id)
   {
       $design = $this->model->findOrFail($design_id);
       return $design->isLikedByUser(auth()->user()->id);
   }

}