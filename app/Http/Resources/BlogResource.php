<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'tags' => $this->tags,
            'description' => $this->description,
            'text' => $this->text,
            'thumbnail' => $this->thumbnail,
            'category' => $this->category,
            'public' => $this->public,
        ];
    }
}
