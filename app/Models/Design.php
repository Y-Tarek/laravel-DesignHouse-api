<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable=[
        'user_id',
        'image',
        'title',
        'description',
        'slug',
        'close_to_comment',
        'is_live',
        'disk',
        'upload_successfuly'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
