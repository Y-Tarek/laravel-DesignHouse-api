<?php
namespace App\Models\Traits;

use App\Models\Like;

/**
 * 
 */
trait Likable
{

  public static function bootlikable()
    {
       static::deleting(function($model){
           $model->removeLikes();
       });
    }

    public function removeLikes()
    {
        if($this->likes()->count()){
            $this->likes()->delete();
        }
    }

  public function likes()
  {
      return $this->morphMany(Like::class,'likable');
  }

   public function Like()
   {
     if(! auth()->check()){
         return;
     }

     if($this->isLikedByUser(auth()->user()->id)){
         return;
     }

     $this->likes()->create([
         'user_id' => auth()->user()->id
     ]);

   }

   public function unlike()
   {
    if(! auth()->check()){
        return;
    }

    if(! $this->isLikedByUser(auth()->user()->id)){
         return;
    }
    $this->likes()->where('user_id',auth()->user()->id)->delete();

   }

   public function isLikedByUser($id)
   {
       return (bool)$this->likes()->where('user_id',$id)->count();
   }
}
