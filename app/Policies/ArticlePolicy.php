<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    /**
     * @param User  $user
     * @param Article $article
     * @return bool
     */
    public function update(User $user, Article $article): bool
    {
        return $this->isUserArticle($user, $article);
    }

    /**
     * @param User  $user
     * @param Article $article
     * @return bool
     */
    public function delete(User $user, Article $article): bool
    {
        return $this->isUserArticle($user, $article);
    }

    /**
     * @param User $user
     * @param Article $article
     * @return bool
     */
    private function isUserArticle(User $user, Article $article): bool
    {
        return $article->user_id === $user->id;
    }
}
