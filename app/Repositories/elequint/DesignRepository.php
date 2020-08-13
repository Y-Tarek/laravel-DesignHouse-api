<?php 
namespace App\Repositories\elequint;
use App\Models\Design;
use Illuminate\Http\Request;
use App\Repositories\Contracts\IDesign;
use  App\Repositories\elequint\BaseRepository;

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

   public function search(Request $request)
   {
      $query = (new $this->model)->newQuery();
      $query->where('is_live', true);

        if($request->has_comments){
            $query->has('comments');
        }

        if($request->has_team){
            $query->has('team');
        }

        if($request->q){
            $query->where(function($q) use ($request){
                $q->where('title', 'like', '%' .$request->q. '%')
                      ->orWhere('description', 'like', '%' .$request->q. '%');
            });
        }

        if($request->orderBy =='likes'){
            $query->withCount('likes')
                  ->orderByDesc('likes_count');
        }
        else{
            $query->latest();
        }

       return $query->get();

   }

}