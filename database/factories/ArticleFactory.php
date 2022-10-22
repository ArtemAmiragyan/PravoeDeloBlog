<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'title' => $this->faker->title(),
            'category' => $this->faker->name(),
            'content' => $this->faker->realText,
            'user_id' => $user->id,
        ];
    }
}
