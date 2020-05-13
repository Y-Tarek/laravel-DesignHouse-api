<?php 
namespace App\Repositories\elequint;
use App\Repositories\Contracts\IComment;
use App\Models\Comment;
use App\Repositories\elequint\BaseRepository;

class CommentRepository extends BaseRepository implements IComment
{
   public function model()
   {
       return Comment::class;//App\Models\Design
   }

   

}