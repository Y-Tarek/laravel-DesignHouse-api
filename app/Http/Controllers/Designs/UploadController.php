<?php

namespace App\Http\Controllers\Designs;

use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function upload(Request $req){
        $this->validate($req,[
           'image' => ['required','mimes:jpg,png,gif,jpeg,bmb','max:2048']
        ]);
        
        $image = $req->file("image");
        $image_path = $image->getPathName();
        $filename = time()."-". preg_replace('/\s+/','-',strtolower($image->getClientOriginalName()));
        
        $tmp = $image->storeAs('uploads/original',$filename,'tmp');
        $design = auth()->user()-> designs()->create([
          "image" => $filename,
          "disk" => config("site.upload_disk"),
        ]);
        $this->dispatch(new UploadImage($design));
        return response()->json($design,200);

    }
}
