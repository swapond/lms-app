<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create test user
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create additional users (instructors and students)
        $instructors = User::factory(5)->create();
        $students = User::factory(20)->create();

        // Create parent categories - slug will auto-generate
        $parentCategories = [
            'Web Development',
            'Mobile Development',
            'Data Science',
            'Design',
            'Business',
            'Marketing',
        ];

        $createdParentCategories = [];
        foreach ($parentCategories as $index => $categoryName) {
            $createdParentCategories[] = Category::firstOrCreate(
                ['name' => $categoryName],
                [
                    'order_index' => $index + 1,
                    'is_published' => true,
                ]
            );
        }

        // Create subcategories
        $subcategories = [
            'Web Development' => ['Laravel', 'React', 'Vue.js', 'Node.js', 'PHP'],
            'Mobile Development' => ['Flutter', 'React Native', 'iOS', 'Android'],
            'Data Science' => ['Python', 'Machine Learning', 'Data Analysis', 'Statistics'],
            'Design' => ['UI/UX', 'Graphic Design', 'Web Design'],
            'Business' => ['Entrepreneurship', 'Management', 'Finance'],
            'Marketing' => ['Digital Marketing', 'SEO', 'Social Media'],
        ];

        foreach ($subcategories as $parentName => $subs) {
            $parent = collect($createdParentCategories)->firstWhere('name', $parentName);
            foreach ($subs as $index => $subName) {
                Category::firstOrCreate(
                    ['name' => $subName],
                    [
                        'parent_id' => $parent->id,
                        'order_index' => $index + 1,
                        'is_published' => true,
                    ]
                );
            }
        }

        // Create courses - slug will auto-generate from name
        $allInstructors = collect([$admin])->concat($instructors);

        $courses = collect();
        $allInstructors->each(function ($instructor) use (&$courses) {
            $instructorCourses = Course::factory(rand(2, 5))
                ->create(['instructor_id' => $instructor->id]);
            $courses = $courses->concat($instructorCourses);
        });

        // Assign categories to courses
        $allCategories = Category::all();
        $courses->each(function ($course) use ($allCategories) {
            $categoryCount = rand(1, 3);
            $selectedCategories = $allCategories->random($categoryCount);

            foreach ($selectedCategories as $index => $category) {
                $course->categories()->attach($category->id, [
                    'is_primary' => $index === 0,
                    'order_index' => $index + 1,
                ]);
            }
        });

        // Create course sections
        $courses->each(function ($course) {
            $sectionCount = rand(3, 8);
            for ($i = 1; $i <= $sectionCount; $i++) {
                CourseSection::factory()->create([
                    'course_id' => $course->id,
                    'order_index' => $i,
                    'title' => "Section {$i}: ".fake()->sentence(3, false),
                ]);
            }
        });

        // Create enrollments
        $publishedCourses = $courses->where('is_published', true);
        $allStudents = collect([$testUser])->concat($students);

        $allStudents->each(function ($student) use ($publishedCourses) {
            $enrollmentCount = rand(1, 5);
            $selectedCourses = $publishedCourses->random(min($enrollmentCount, $publishedCourses->count()));

            foreach ($selectedCourses as $course) {
                $enrollment = Enrollment::factory()->create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'amount_paid' => $course->price,
                    'payment_method' => $course->price > 0 ? 'credit_card' : 'free',
                ]);

                if ($enrollment->status === 'completed') {
                    Certificate::factory()->create([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'enrollment_id' => $enrollment->id,
                        'title' => "Certificate of Completion - {$course->name}",
                    ]);
                }
            }
        });

        $this->command->info('Database seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- Users: '.User::count());
        $this->command->info('- Categories: '.Category::count());
        $this->command->info('- Courses: '.Course::count());
        $this->command->info('- Course Sections: '.CourseSection::count());
        $this->command->info('- Enrollments: '.Enrollment::count());
        $this->command->info('- Certificates: '.Certificate::count());
    }
}
