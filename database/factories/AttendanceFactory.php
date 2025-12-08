<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get or create a class
        $class = ClassRoom::inRandomOrder()->first() ?? ClassRoom::factory()->create();

        // Get or create a student for this class
        $student = Student::where('class_id', $class->id)->inRandomOrder()->first()
            ?? Student::factory()->forClass($class)->create();

        // Get or create a teacher for this class
        $teacher = Teacher::where('class_id', $class->id)->inRandomOrder()->first()
            ?? Teacher::factory()->forClass($class)->create();

        $statuses = ['Present', 'Absent'];

        // Generate a date within the last 6 months (avoid future dates)
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now()->subDay(); // Yesterday, not today

        return [
            'class_id' => $class->id,
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'date' => $this->faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d'),
            'status' => $this->faker->randomElement($statuses),
        ];
    }

    /**
     * Indicate attendance is present.
     */
    public function present(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'Present',
        ]);
    }

    /**
     * Indicate attendance is absent.
     */
    public function absent(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'Absent',
        ]);
    }

    /**
     * Indicate specific date.
     */
    public function date(string $date): static
    {
        return $this->state(fn(array $attributes) => [
            'date' => $date,
        ]);
    }

    /**
     * Configure the model factory to ensure unique attendance records.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Attendance $attendance) {
            // Ensure unique combination of class, student, and date
            // Generate a new date if the combination already exists
            while (
                Attendance::where('class_id', $attendance->class_id)
                ->where('student_id', $attendance->student_id)
                ->where('date', $attendance->date)
                ->exists()
            ) {
                // Generate a new date within the last 6 months
                $attendance->date = Carbon::now()
                    ->subDays(rand(1, 180))
                    ->format('Y-m-d');
            }
        });
    }
}
