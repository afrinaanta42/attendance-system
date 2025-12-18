<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Role;
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = Role::where('name', 'Student')->first();
        $classes = ClassRoom::all();

        // Create specific students for demo
        $students = [
            [
                'name' => 'Alice Johnson',
                'email' => 'student@example.com',
                'password' => Hash::make('password'),
                'roll_number' => 'ROLL24001',
                'class_id' => $classes->where('class_name', 'Class 10')->where('section', 'A')->first()->id,
                'gender' => 'Female',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'student.bob@attendance.com',
                'password' => Hash::make('student123'),
                'roll_number' => 'ROLL24002',
                'class_id' => $classes->where('class_name', 'Class 10')->where('section', 'B')->first()->id,
                'gender' => 'Male',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'student.charlie@attendance.com',
                'password' => Hash::make('student123'),
                'roll_number' => 'ROLL24003',
                'class_id' => $classes->where('class_name', 'Class 11')->where('section', 'A')->first()->id,
                'gender' => 'Male',
            ],
            [
                'name' => 'Diana Prince',
                'email' => 'student.diana@attendance.com',
                'password' => Hash::make('student123'),
                'roll_number' => 'ROLL24004',
                'class_id' => $classes->where('class_name', 'Class 11')->where('section', 'B')->first()->id,
                'gender' => 'Female',
            ],
            [
                'name' => 'Edward Lee',
                'email' => 'student.edward@attendance.com',
                'password' => Hash::make('student123'),
                'roll_number' => 'ROLL24005',
                'class_id' => $classes->where('class_name', 'Class 12')->where('section', 'A')->first()->id,
                'gender' => 'Male',
            ],
        ];

        foreach ($students as $studentData) {
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => $studentData['password'],
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );

            // Attach student role
            if ($studentRole) {
                $user->roles()->syncWithoutDetaching([$studentRole->id]);
            }

            // Create student profile
            Student::firstOrCreate(
                ['roll_number' => $studentData['roll_number']],
                [
                    'user_id' => $user->id,
                    'class_id' => $studentData['class_id'],
                    'dob' => now()->subYears(rand(15, 20))->format('Y-m-d'),
                    'gender' => $studentData['gender'],
                ]
            );
        }

        // Create additional random students using factory
        Student::factory()->count(20)->create()->each(function ($student) use ($studentRole) {
            if ($studentRole) {
                $student->user->roles()->syncWithoutDetaching([$studentRole->id]);
            }
        });

        $this->command->info('Students seeded successfully!');
        $this->command->info('Student login credentials:');
        $this->command->info('Email: student.alice@attendance.com');
        $this->command->info('Password: student123');
    }
}
