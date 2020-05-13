<?php

namespace App\Http\Controllers\Designs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Contracts\IComment;

class CommentController extends Controller
{
    protected $comments;
     protected $designs;

     public function __construct(IComment $comments, IDesign $designs)
     {
         $this->comments = $comments;
         $this->designs = $designs;
     }

     public function store(Request $req, $design_id)
     {
        $this->validate($req,[
            "body" => ['required']
        ]);
      $comment =  $this->designs->addComments($design_id, [
            "body" => $req->body,
            "user_id" => auth()->user()->id
        ]);
         return new CommentResource($comment);

     }

     public function update(Request $req, $id)
     {
         $comment = $this->comments->find($id);
         $this->authorize('update',$comment);

           $this->validate($req,[
             "body" => ['required'],
         ]);

        $comment=  $this->comments->update($id, [
             "body" => $req->body,
         ]);

         return new CommentResource($comment);

     }

     public function destroy($id)
     {
        $comment = $this->comments->find($id);
        $this->authorize('delete',$comment);
        $this->comments->delete($id);
        return response()->json("Comment Deleted",200);
     }


}
