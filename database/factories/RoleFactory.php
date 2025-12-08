<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
        ];
    }

    /**
     * Indicate that the role is Admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Admin',
            'description' => 'System Administrator with full access',
        ]);
    }

    /**
     * Indicate that the role is Teacher.
     */
    public function teacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Teacher',
            'description' => 'Teacher role for managing classes and attendance',
        ]);
    }

    /**
     * Indicate that the role is Student.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Student',
            'description' => 'Student role for viewing attendance',
        ]);
    }
}