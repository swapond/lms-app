<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'enrollment_id' => Enrollment::factory(),
            'certificate_number' => 'CERT-'.fake()->unique()->numerify('######'),
            'title' => fake()->sentence(4, false),
            'verification_code' => fake()->unique()->regexify('[A-Z0-9]{12}'),
            'issued_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
