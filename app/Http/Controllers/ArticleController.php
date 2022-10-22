<?php

namespace App\Http\Controllers;

use App\Filters\ArticleFilters;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\Article\ArticleCollection;
use App\Http\Resources\Article\ArticleResource;
use App\Models\Article;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class ArticleController extends Controller
{
    /**
     * @param ArticleFilters $filters
     * @return ArticleCollection
     */
    public function index(ArticleFilters $filters): ArticleCollection
    {
        return new ArticleCollection(
            Article::filter($filters)
                ->with('author')
                ->with('comments')
                ->paginate()
        );
    }

    /**
     * @param ArticleRequest $request
     * @return Article
     */
    public function store(ArticleRequest $request): Article
    {
        return tap((new Article())
            ->fill($request->validated())
            ->author()
            ->associate($request->user()))
            ->save();
    }

    /**
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article): ArticleResource
    {
        return new ArticleResource($article->load('comments.author', 'author'));
    }

    /**
     * @param Article $article
     * @param ArticleRequest $request
     * @return Article
     */
    public function update(Article $article, ArticleRequest $request): Article
    {
        return tap($article)
            ->update($request->validated());
    }

    /**
     * @param Article $article
     * @return bool|null
     */
    public function destroy(Article $article): ?bool
    {
        $article->comments()->delete();

        return $article->delete();
    }

    public function getCategories(): Collection
    {
        return Article::pluck('category');
    }
}
