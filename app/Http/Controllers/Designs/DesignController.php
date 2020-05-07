<?php

namespace App\Http\Controllers\Designs;

use App\Repositories\Contracts\IDesign;
use App\Models\Design;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    protected $designs;

    public function __construct(IDesign $designs)
    {
       $this->designs = $designs;
    }

    public function index(){
        $design = $this->designs->all();
        return DesignResource::collection($design);
    }

    public function update(Request $req, $id){
        $design = Design::find($id);
        $this->authorize('update',$design);
        $this->validate($req,[
          "title" => ['required','unique:designs,title,'.$id],
          "description" => ['required','string','min:20','max:140'],
          'tags' => ['required'],
        ]);
        $design->update([
          'title' => $req->title,
          'description' => $req->description,
          'slug' => Str::slug($req->title),
          'is_live' => ! $design->upload_successfuly ? false : $req->is_live
        ]);
        $design->retag($req->tags);
        return new DesignResource($design);
    }

    public function destroy($id){
       $design =  Design::findOrFail($id);
        $this->authorize('delete',$design);

        
        foreach(['thumbnail','large','original'] as $size){
            if(Storage::disk($design->disk)->exists("uploads/designs/{$size}/". $design->image)){
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/". $design->image);
            }

        }
        $design->delete();
        return response()->json("Deleted suuccessfuly",200);
    }
}
