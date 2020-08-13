<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Rules\CheckSamePassword;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Geometry;


class SettingsController extends Controller
{
    public function updateProfile(Request $req){
        $user = auth()->user();
        $this->validate($req,[
            'name' => ['required'],
            'tagline' => ['required'],
            'about' => ['required','string','min:20'],
            'available_to_hire' => ['required'],
            'formatted_address' => ['required'],
            'location.latitude' => ['required'],
            'location.longtitude' => ['required']
        ]);
        $location = new Point($req->location['latitude'],$req->location['longtitude']);
        $user->update([
            'name' => $req->name,
            'tagline' => $req->tagline,
            'about' => $req->about,
            'available_to_hire' => $req->available_to_hire,
            'location' => $location,
            'formatted_address' => $req->formatted_address
        ]);
        return new UserResource($user);
    }

    public function updatePassword(Request $req){
        $this->validate($req,[
          "current_password" => ['required',new MatchOldPassword],
          "password" => ['required','confirmed','min:6',new CheckSamePassword]
        ]);
        $user = auth()->user();
        $user->update([
            "password" => bcrypt($req->password)
        ]);
        return response()->json(["message" => "password updated"],200);
    }
}
