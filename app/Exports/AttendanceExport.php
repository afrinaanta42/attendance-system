<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Attendance::with(['student.user', 'classRoom']);

        if ($this->request->month) {
            $month = $this->request->month;
            $query->whereYear('date', substr($month,0,4))
                  ->whereMonth('date', substr($month,5,2));
        }
        if ($this->request->class_id) $query->where('class_id', $this->request->class_id);
        if ($this->request->student_id) $query->where('student_id', $this->request->student_id);

        return $query->get();
    }

    public function headings(): array
    {
        return ['Date', 'Student Name', 'Roll No', 'Class', 'Status', 'Marked By'];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date->format('d-m-Y'),
            $attendance->student->user->name,
            $attendance->student->roll_number,
            $attendance->classRoom->class_name . ' ' . $attendance->classRoom->section,
            $attendance->status,
            $attendance->teacher->user->name ?? 'N/A',
        ];
    }
}
