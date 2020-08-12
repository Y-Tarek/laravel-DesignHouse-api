<?php

namespace App\Models;

use App\Models\Team;
use App\Models\User;
use App\Models\Comment;
use App\Models\Traits\Likable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentTaggable\Taggable;

class Design extends Model
{
 use Taggable,Likable;
    protected $fillable=[
        'user_id',
        'team_id',
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

    public function team()
    {
        return 
        $this->belongsTo(Team::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable')
        ->orderBy('created_at', 'asc');
    }

    public function getImagesAttribute(){
        return [
          'thumbnail' => $this->getImagePath('thumbanil'),
          'large' => $this->getImagePath('large'),
          'original' => $this->getImagePath('original'),
        ];
    }

    protected function getImagePath($size){
        return Storage::disk($this->disk)
                         ->url("uploads/designs/{$size}/" . $this->image);
    }
}
