<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseSectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(4, false),
            'description' => fake()->paragraph(),
            'order_index' => fake()->numberBetween(1, 20),
            'is_published' => fake()->boolean(85),
        ];
    }
}
