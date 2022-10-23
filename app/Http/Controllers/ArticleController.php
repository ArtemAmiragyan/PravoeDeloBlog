<?php

namespace App\Http\Controllers;

use App\Filters\ArticleFilters;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\Article\ArticleCollection;
use App\Http\Resources\Article\ArticleResource;
use App\Models\Article;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class ArticleController extends Controller
{
    use AuthorizesRequests;

    /**
     * @param ArticleFilters $filters
     * @return ArticleCollection
     */
    public function index(ArticleFilters $filters): ArticleCollection
    {
        return new ArticleCollection(
            Article::filter($filters)
                ->with('author')
                ->withCount('comments')
                ->latest()
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
     * @throws AuthorizationException
     */
    public function update(Article $article, ArticleRequest $request): Article
    {
        $this->authorize($article);

        return tap($article)
            ->update($request->validated());
    }

    /**
     * @param Article $article
     * @return bool|null
     * @throws AuthorizationException
     */
    public function destroy(Article $article): ?bool
    {
        $this->authorize($article);

        $article->comments()->delete();

        return $article->delete();
    }

    public function getCategories(): Collection
    {
        return Article::select('category')->distinct()->pluck('category');
    }
}
