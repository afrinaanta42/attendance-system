<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacherRole = Role::where('name', 'Teacher')->first();
        $classes = ClassRoom::all();

        // Create specific teachers
        $teachers = [
            [
                'name' => 'John Smith',
                'email' => 'teacher@example.com',
                'password' => Hash::make('password'),
                'subject' => 'Mathematics',
                'class_id' => $classes->where('subject', 'Mathematics')->first()->id ?? null,
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'teacher.sarah@attendance.com',
                'password' => Hash::make('teacher123'),
                'subject' => 'Science',
                'class_id' => $classes->where('subject', 'Science')->first()->id ?? null,
            ],
            [
                'name' => 'Robert Brown',
                'email' => 'teacher.robert@attendance.com',
                'password' => Hash::make('teacher123'),
                'subject' => 'Physics',
                'class_id' => $classes->where('subject', 'Physics')->first()->id ?? null,
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'teacher.emma@attendance.com',
                'password' => Hash::make('teacher123'),
                'subject' => 'Chemistry',
                'class_id' => $classes->where('subject', 'Chemistry')->first()->id ?? null,
            ],
            [
                'name' => 'Michael Davis',
                'email' => 'teacher.michael@attendance.com',
                'password' => Hash::make('teacher123'),
                'subject' => 'Computer Science',
                'class_id' => $classes->where('subject', 'Computer Science')->first()->id ?? null,
            ],
        ];

        foreach ($teachers as $teacherData) {
            $user = User::firstOrCreate(
                ['email' => $teacherData['email']],
                [
                    'name' => $teacherData['name'],
                    'password' => $teacherData['password'],
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );

            // Attach teacher role
            if ($teacherRole) {
                $user->roles()->syncWithoutDetaching([$teacherRole->id]);
            }

            // Create teacher profile
            Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'class_id' => $teacherData['class_id'],
                    'subject' => $teacherData['subject'],
                ]
            );
        }

        // Create additional random teachers using factory
        Teacher::factory()->count(5)->create()->each(function ($teacher) use ($teacherRole) {
            if ($teacherRole) {
                $teacher->user->roles()->syncWithoutDetaching([$teacherRole->id]);
            }
        });

        $this->command->info('Teachers seeded successfully!');
        $this->command->info('Teacher login credentials:');
        $this->command->info('Email: teacher.john@attendance.com');
        $this->command->info('Password: teacher123');
    }
}
