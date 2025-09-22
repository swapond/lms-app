<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['active', 'completed']);

        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'status' => $status,
            'enrolled_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'completed_at' => $status === 'completed' ? fake()->dateTimeBetween('-3 months', 'now') : null,
            'progress_percentage' => $status === 'completed' ? 100 : fake()->numberBetween(0, 95),
            'amount_paid' => fake()->randomFloat(2, 0, 500),
            'payment_method' => fake()->randomElement(['credit_card', 'paypal', 'free']),
        ];
    }
}
