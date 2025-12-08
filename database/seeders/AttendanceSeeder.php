<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = ClassRoom::all();

        foreach ($classes as $class) {
            $students = $class->students;
            $teacher = $class->teacher;

            if ($students->isEmpty() || !$teacher) {
                continue;
            }

            // Generate attendance for the last 60 days (more buffer)
            for ($i = 1; $i <= 60; $i++) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');

                // Skip weekends
                if (Carbon::parse($date)->isWeekend()) {
                    continue;
                }

                foreach ($students as $student) {
                    // Check if attendance already exists for this combination
                    $exists = Attendance::where('class_id', $class->id)
                        ->where('student_id', $student->id)
                        ->where('date', $date)
                        ->exists();

                    if (!$exists) {
                        // Randomly mark attendance (85% present rate)
                        $status = rand(1, 100) <= 85 ? 'Present' : 'Absent';

                        Attendance::create([
                            'class_id' => $class->id,
                            'student_id' => $student->id,
                            'teacher_id' => $teacher->id,
                            'date' => $date,
                            'status' => $status,
                        ]);
                    }
                }
            }
        }

        $this->command->info('Attendance records seeded successfully!');

        // Now create additional records using factory but with unique constraints
        $this->createFactoryAttendance();
    }

    /**
     * Create additional attendance records using factory with unique constraints
     */
    private function createFactoryAttendance(): void
    {
        $batchSize = 50;
        $created = 0;
        $attempts = 0;
        $maxAttempts = 200; // Safety limit to prevent infinite loop

        while ($created < 100 && $attempts < $maxAttempts) {
            try {
                Attendance::factory()->create();
                $created++;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Duplicate entry, just continue
                $attempts++;
                continue;
            } catch (\Exception $e) {
                $attempts++;
                continue;
            }
        }

        $this->command->info("Created {$created} additional attendance records.");
    }
}
