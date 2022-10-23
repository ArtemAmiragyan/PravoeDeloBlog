<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        return [
            'content' => $this->faker->realText,
            'user_id' => $user->id,
            'article_id' => $article->id,
        ];
    }
}
