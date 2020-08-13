<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    
    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getLatestMessageAttribute()
    {
       return $this->messages()
                   ->latest()
                   ->first();
    }

    public function isUnreadForUser($user_id)
    {
        return (bool)$this->messages()
                          ->whereNull('last_read')
                          ->where('user_id', '<>' ,$user_id)
                          ->count();
    
    }

    public function markMessageAsRead($user_id)
    {
        $this->messages()
                        ->whereNull('last_read')
                        ->where('user_id', '<>' ,$user_id)
                        ->update([
                            'last_read' => Carbon::now()
                            ]);
    }
}
