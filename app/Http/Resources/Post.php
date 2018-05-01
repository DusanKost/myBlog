<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'post_id' => $this->id,
            'post_title' => $this->post_title,
            'post_body' => $this->post_body,
            'post_img' => $this->post_img,
            'user_name' => User::findOrFail($this->user_id)->name,
            'user_id' => User::findOrFail($this->user_id)->id
        ];
    }
}
