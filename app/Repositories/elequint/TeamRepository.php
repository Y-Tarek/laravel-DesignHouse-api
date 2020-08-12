<?php 
namespace App\Repositories\elequint;
use App\Models\Team;
use App\Repositories\Contracts\ITeam;
use App\Repositories\elequint\BaseRepository;

class TeamRepository extends BaseRepository implements ITeam
{
   public function model()
   {
       return Team::class;//App\Models\Team
   }

   public function fetchUserTeams()
   {
       return auth()->user()->teams();
   }

   

}