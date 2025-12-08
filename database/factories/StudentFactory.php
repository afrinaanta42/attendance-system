<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genders = ['Male', 'Female', 'Other'];

        // Generate unique roll number
        $year = now()->format('y');
        $randomDigits = $this->faker->unique()->numberBetween(1000, 9999);
        $rollNumber = "ROLL{$year}{$randomDigits}";

        return [
            'user_id' => User::factory(),
            'roll_number' => $rollNumber,
            'class_id' => ClassRoom::factory(),
            'dob' => $this->faker->dateTimeBetween('-20 years', '-15 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement($genders),
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
     * Indicate specific gender.
     */
    public function gender(string $gender): static
    {
        return $this->state(fn(array $attributes) => [
            'gender' => $gender,
        ]);
    }
}
