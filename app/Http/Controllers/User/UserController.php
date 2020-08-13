<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\IUser;
use App\Repositories\elequint\criteria\EagerLoad;

class UserController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
       $this->users = $users;
    }

    public function index(){
        $users = $this->users->withCriteria([
            new EagerLoad(['designs'])
        ])->all();
        return UserResource::collection($users);
    }

    public function search(Request $req)
    {
        $designers = $this->users->search($req);
        return UserResource::collection($designers);
    }
}
