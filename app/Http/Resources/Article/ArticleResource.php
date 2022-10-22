<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, string|array<string, string>>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'category' => $this->resource->category,
            'author_name' => $this->resource->author->name,
            'comments' => $this->resource->comments->each(fn ($comment) => [
                'author_name' => $comment->author->name,
                'content' => $comment->content,
                'date' => $comment->created_at->toFormattedDateString(),
            ]),
            'date' => $this->resource->created_at->toFormattedDateString(),
        ];
    }
}
