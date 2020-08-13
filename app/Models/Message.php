<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{

 use SoftDeletes;

    protected $touches = ['chat'];
    protected $fillable = [
        'user_id',
        'chat_id',
        'body',
        'last_read'
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
