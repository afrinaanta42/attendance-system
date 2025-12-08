<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $classes = [
            'Class 10',
            'Class 11',
            'Class 12',
            'BSc 1st Year',
            'BSc 2nd Year',
            'BSc 3rd Year',
            'BSc 4th Year',
            'MSc 1st Year',
            'MSc 2nd Year',
            'MBA 1st Semester',
            'MBA 2nd Semester',
            'MBA 3rd Semester',
            'MBA 4th Semester'
        ];

        $sections = ['A', 'B', 'C', 'D'];
        $subjects = ['Mathematics', 'Science', 'English', 'History', 'Physics', 'Chemistry', 'Biology', 'Computer Science', 'Business Studies', 'Economics'];

        return [
            'class_name' => $this->faker->randomElement($classes),
            'section' => $this->faker->randomElement($sections),
            'subject' => $this->faker->randomElement($subjects),
        ];
    }

    /**
     * Indicate specific class name.
     */
    public function className(string $className): static
    {
        return $this->state(fn(array $attributes) => [
            'class_name' => $className,
        ]);
    }

    /**
     * Indicate no section.
     */
    public function noSection(): static
    {
        return $this->state(fn(array $attributes) => [
            'section' => null,
        ]);
    }
}
