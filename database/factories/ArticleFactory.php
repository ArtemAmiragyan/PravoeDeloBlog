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
            'title' => $this->faker->text(50),
            'category' => $this->faker->text(20),
            'content' => $this->faker->realText(20000),
            'user_id' => $user->id,
        ];
    }
}
