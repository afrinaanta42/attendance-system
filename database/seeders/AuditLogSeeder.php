<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some audit logs for demo
        AuditLog::factory()->count(50)->create();

        $this->command->info('Audit logs seeded successfully!');
    }
}
