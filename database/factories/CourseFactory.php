<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->sentence(3, false);

        return [
            'instructor_id' => User::factory(),
            'name' => $name,
            'description' => fake()->paragraphs(2, true),
            'short_description' => fake()->sentence(),
            'learning_objectives' => [fake()->sentence(), fake()->sentence()],
            'prerequisites' => [fake()->sentence()],
            'language' => 'en',
            'price' => fake()->randomFloat(2, 0, 500),
            'is_published' => fake()->boolean(80),
            'is_featured' => fake()->boolean(20),
            'duration_minutes' => fake()->numberBetween(60, 600),
        ];
    }
}
