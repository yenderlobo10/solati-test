<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(20),
            'description' => $this->faker->text,
            'finished' => $this->faker->boolean,
            'user_id' => $this->faker->numberBetween(1, 10),
            'created_at' => $this->faker->dateTimeThisMonth,
        ];
    }
}
