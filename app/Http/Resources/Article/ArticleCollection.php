<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class ArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array{data: Collection<(int|string), array{id: mixed, title: mixed, category: mixed, author_name: mixed, content: mixed, comments_count: mixed,date: mixed}>}
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'category' => $article->category,
                    'author_name' => $article->author->name,
                    'content' => $article->content,
                    'comments_count' => $article->comments_count,
                    'date' => $article->created_at->toFormattedDateString(),
                ];
            }),
        ];
    }
}
