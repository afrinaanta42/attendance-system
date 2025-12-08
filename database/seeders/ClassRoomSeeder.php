<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            [
                'class_name' => 'Class 10',
                'section' => 'A',
                'subject' => 'Mathematics',
            ],
            [
                'class_name' => 'Class 10',
                'section' => 'B',
                'subject' => 'Science',
            ],
            [
                'class_name' => 'Class 11',
                'section' => 'A',
                'subject' => 'Physics',
            ],
            [
                'class_name' => 'Class 11',
                'section' => 'B',
                'subject' => 'Chemistry',
            ],
            [
                'class_name' => 'Class 12',
                'section' => 'A',
                'subject' => 'Computer Science',
            ],
            [
                'class_name' => 'Class 12',
                'section' => 'B',
                'subject' => 'Biology',
            ],
            [
                'class_name' => 'BSc 1st Year',
                'section' => null,
                'subject' => 'Computer Science',
            ],
            [
                'class_name' => 'BSc 2nd Year',
                'section' => null,
                'subject' => 'Computer Science',
            ],
            [
                'class_name' => 'BSc 3rd Year',
                'section' => null,
                'subject' => 'Computer Science',
            ],
            [
                'class_name' => 'MBA 1st Semester',
                'section' => null,
                'subject' => 'Business Administration',
            ],
        ];

        foreach ($classes as $class) {
            ClassRoom::firstOrCreate(
                ['class_name' => $class['class_name'], 'section' => $class['section']],
                $class
            );
        }

        // Create additional random classes using factory
        ClassRoom::factory()->count(5)->create();

        $this->command->info('Classes seeded successfully!');
    }
}
