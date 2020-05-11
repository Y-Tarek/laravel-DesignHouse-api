<?php

namespace App\Http\Controllers\Designs;

use App\Models\Design;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Repositories\Contracts\IDesign;
use Illuminate\Support\Facades\Storage;
use App\Repositories\elequint\criteria\IsLive;
use App\Repositories\elequint\criteria\ForUser;
use App\Repositories\elequint\criteria\LatestFirst;

class DesignController extends Controller
{
    protected $designs;

    public function __construct(IDesign $designs)
    {
       $this->designs = $designs;
    }

    public function index(){
        $design = $this->designs->withCriteria([
            new LatestFirst(),
            new IsLive(),
            new ForUser(1)
        ])->all();
        return DesignResource::collection($design);
    }

    public function getDesignById($id)
    {
        $design = $this->designs->find($id);
        return new DesignResource($design);
    }

    public function update(Request $req, $id){
        $design = $this->designs->find($id);
        $this->authorize('update',$design);
        $this->validate($req,[
          "title" => ['required','unique:designs,title,'.$id],
          "description" => ['required','string','min:20','max:140'],
          'tags' => ['required'],
        ]);
        $upd_design= $this->designs->update($id, [
          'title' => $req->title,
          'description' => $req->description,
          'slug' => Str::slug($req->title),
          'is_live' => ! $design->upload_successfuly ? false : $req->is_live
        ]);
        $this->designs->applyTags($id,$req->tags);
        return new DesignResource($upd_design);
    }

    public function destroy($id){
       $design =  $this->designs->find($id);
        $this->authorize('delete',$design);

        foreach(['thumbnail','large','original'] as $size){
            if(Storage::disk($design->disk)->exists("uploads/designs/{$size}/". $design->image)){
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/". $design->image);
            }
        }
        $this->designs->delete($id);
        return response()->json("Deleted suuccessfuly",200);
    }
}
