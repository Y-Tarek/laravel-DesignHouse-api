<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       
        return [
             'id' => $this->id,
             'dates' => [
               'created_at' => $this->created_at,
               'created_at_human' => $this->created_at->diffForHumans(),
             ],
             'seen' => $this->isUnreadForUser(auth()->user()->id) ? 'seen' 
             : 'sent',
             'latest_message' => new MessageResource($this->latest_message),
             'participants' => UserResource::collection($this->participants)
        ];
    }
}
