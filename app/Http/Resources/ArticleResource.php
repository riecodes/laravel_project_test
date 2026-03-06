<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'            =>$this->id,
            'title'         =>$this->title,
            'content'       =>$this->content,
            'author'        =>$this->user->name, // Accessing the relationship
            'created_at'    =>$this->created_at->toIso8601String(),
        ];
    }
}
