<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            "body" => $this->body,
            'created_at_dates' => [
                'created_at_humans' => $this->created_at->diffForHumans(),
                'created_at'=> $this->created_at
            ],
            'upadated_at_dates' => [
                'updated_at_humans' => $this->updated_at->diffForHumans(),
                'updated_at'=> $this->updated_at
            ],
            'user' => new UserResource($this->user),

        ];
    }
}
