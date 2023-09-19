<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
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
            'type' => 'Posts',
            'attributes' => [
                'author' => $this->author,
                'content' => $this->content,
                'user_id' => $this->user_id,
                'liked_from' => $this->liked_from
            ]
        ];
    }
}
