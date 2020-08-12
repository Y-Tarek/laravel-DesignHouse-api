<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'name'=> $this->name,
            'email' => $this->email,
            'about' => $this->about,
            'designs' => DesignResource::collection($this->whenLoaded('designs')),
            'tagline' => $this->tagline,
            'formatted_address' => $this->formatted_address,
            'location'=> $this->location,
            'available_to_hire'=> $this->available_to_hire,
            'dates' => [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]
         ];
    }
}
