<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create default roles
    $roles = [
      [
        'name' => 'Admin',
        'description' => 'System Administrator with full access',
      ],
      [
        'name' => 'Teacher',
        'description' => 'Teacher role for managing classes and attendance',
      ],
      [
        'name' => 'Student',
        'description' => 'Student role for viewing attendance',
      ],
    ];

    foreach ($roles as $role) {
      Role::firstOrCreate(
        ['name' => $role['name']],
        $role
      );
    }

    $this->command->info('Roles seeded successfully!');
  }
}
