<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actions = [
            'User Login',
            'User Logout',
            'Added Student',
            'Updated Student',
            'Deleted Student',
            'Added Teacher',
            'Updated Teacher',
            'Deleted Teacher',
            'Added Class',
            'Updated Class',
            'Deleted Class',
            'Marked Attendance',
            'Updated Attendance',
            'Exported Report',
            'User Registered',
            'Password Changed',
            'Profile Updated'
        ];

        $users = User::pluck('id')->toArray();

        return [
            'user_id' => count($users) > 0 ? $this->faker->randomElement($users) : User::factory(),
            'action' => $this->faker->randomElement($actions),
            'details' => $this->faker->sentence(10),
            'ip_address' => $this->faker->ipv4(),
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
     * Indicate specific action.
     */
    public function action(string $action): static
    {
        return $this->state(fn(array $attributes) => [
            'action' => $action,
        ]);
    }
}
