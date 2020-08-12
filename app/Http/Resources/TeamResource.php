<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'designs' => DesignResource::collection($this->designs),
            'total_members' => $this->members->count(),
            'owner' => new UserResource($this->owner),
            'members' => UserResource::collection($this->members)
        ];
    }
}
