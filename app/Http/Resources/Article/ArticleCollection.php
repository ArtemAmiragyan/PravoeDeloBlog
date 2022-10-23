<?php

namespace App\Http\Resources\Article;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array{data: Collection<(int|string), array{id: mixed, title: mixed, category: mixed, author_name: mixed, content: string,comments_count: mixed, date: mixed, can: array{update: bool, delete: bool}}>}
     */
    public function toArray($request): array
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        return [
            'data' => $this->collection->map(function ($article) use ($authUser) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'category' => $article->category,
                    'author_name' => $article->author->name,
                    'content' => Str::limit($article->content, 300, '...') ,
                    'comments_count' => $article->comments_count,
                    'date' => $article->created_at->toFormattedDateString(),
                    'can' => [
                        'update' => $authUser->can('update', $article->resource),
                        'delete' => $authUser->can('delete', $article->resource),
                    ],
                ];
            }),
        ];
    }
}
