<?php

namespace App\Http\Resources\Article;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'category' => $this->resource->category,
            'author_name' => $this->resource->author->name,
            'content' => $this->resource->content,
            'comments' => $this->resource->comments->map(fn ($comment) => [
                'author_name' => $comment->author->name,
                'content' => $comment->content,
                'date' => $comment->created_at->toFormattedDateString(),
            ]),
            'date' => $this->resource->created_at->toFormattedDateString(),
            'can' => [
                'update' => $authUser->can('update', $this->resource),
                'delete' => $authUser->can('delete', $this->resource),
            ],
        ];
    }
}
