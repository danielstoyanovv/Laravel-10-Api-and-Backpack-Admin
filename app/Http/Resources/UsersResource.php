<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'type' => 'Users',
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status,
                'image' => $this->image,
                'short_description' => $this->short_description,
                'token' => $this->token,
                'refresh_token' => $this->refresh_token,
                'expires_at' => $this->expires_at
            ]
        ];
    }
}
