<?php

namespace App\Http\Controllers\Teams;

use App\Repositories\Contracts\IInvitation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Contracts\IUser;

class TeamsController extends Controller
{
    protected $users, $teams, $invitations;

     public function __construct(IUser $users, ITeam $teams, IInvitation $invitations)
     {
         $this->users = $users;
         $this->teams = $teams;
         $this->invitations = $invitations;
     }

     public function store(Request $req)
     {
         $this->validate($req,[
          "name" => ['required', 'string', 'max:90', 'unique:teams,name']

         ]);
         $team = $this->teams->create([
            'name' => $req->name,
            'slug' => Str::slug($req->name),
            'owner_id' => auth()->user()->id
         ]);
         return new TeamResource($team);
     }

     public function update(Request $req,$id)
     {
         $team = $this->teams->find($id);
         $this->authorize("update",$team);

         $this->validate($req,[
            'name' => ['required','string','max:90','unique:teams,name,'.$id]
         ]);
         $team = $this->teams->update($id,[
             'name' => $req->name,
             'slug' => Str::slug($req->name)
         ]);
         return new TeamResource($team);
     }

     public function destroy($id)
     {
         $team = $this->teams->find($id);
         $this->authorize('delete',$team);

         $this->teams->delete($id);
         return response()->json([
             "message" => "Team deleted successfuly"
         ],200);
     }

     public function findById($id)
     {
         $team = $this->teams->find($id);
         return new TeamResource($team);
     }

     public function fetchUserTeams()
     {
         $teams = $this->teams->fetchUserTeams();
         return  TeamResource::collection($teams);
     }

     public function deleteFromTeam($teamId, $userId)
     {
         $team = $this->teams->find($teamId);
         $user = $this->users->find($userId);
           if($user->isOwnerOfTeam($team)){
               return response()->json([
                   "message" => "You are not allowed to remove yourself untill Team is empty."
               ],422);
           }

           if(! $user->isOwnerOfTeam($team) && 
              auth()->user()->id !== $user->id)
              {
                return response()->json([
                    "message" => "You are not allowed to remove anyone."
                ],422);
              }

              $this->invitations->deleteFromTeam($team, $userId);
              return response()->json([
                "message" => "Member deleted."
            ],200);
     }

}
