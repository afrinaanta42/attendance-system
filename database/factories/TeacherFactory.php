<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = ['Mathematics', 'Science', 'English', 'History', 'Physics', 'Chemistry', 'Biology', 'Computer Science', 'Business Studies', 'Economics'];

        return [
            'user_id' => User::factory(),
            'class_id' => ClassRoom::factory(),
            'subject' => $this->faker->randomElement($subjects),
        ];
    }

    /**
     * Indicate specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Indicate specific class.
     */
    public function forClass(ClassRoom $class): static
    {
        return $this->state(fn(array $attributes) => [
            'class_id' => $class->id,
        ]);
    }

    /**
     * Indicate no assigned class.
     */
    public function noClass(): static
    {
        return $this->state(fn(array $attributes) => [
            'class_id' => null,
        ]);
    }
}
