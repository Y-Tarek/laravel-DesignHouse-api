<?php

namespace App\Models;

use App\Models\User;
use App\Models\Design;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Model;

class Team  extends Model
{
    protected $fillable = [
       'name',
       'owner_id',
       'slug'
    ];

    protected static function boot()
    {
        parent::boot();
      //When Team is created add user as a team member  
        static::created(function($team){
           $team->members()->attach(auth()->user()->id);
        });
      // when Team is deleted delete all members (in team_user table)
        static::deleting(function($team){
            $team->members()->sync([]);
        });
    }

    public function owner() 
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
                            ->withTimeStamps();
    }

    public function designs()
    {
       return  $this->hasMany(Design::class);
    }

    public function hasUser(User $user)
    {
      return (bool)$this->members()
                        ->where('user_id', $user->id)
                        ->count();
    }

    public function invitations()
    {
      return $this->hasMany(Invitation::class);
    }

    public function hasPendingInvitation($email)
    {
      return (bool)$this->invitations()
                  ->where('recipient_email',$email)
                  ->count();
    }
}
