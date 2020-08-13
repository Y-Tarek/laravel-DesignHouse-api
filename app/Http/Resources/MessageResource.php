<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'message' => $this->trashed() ? 'This message was deleted' : $this->body,
            'deleted' => $this->trashed(),
            'dates' => [
                'created_at' => $this->created_at,
                'created_at_human' => $this->created_at->diffForHumans(),
            ],
            'sender' => new UserResource($this->sender)
        ];
    }
}
