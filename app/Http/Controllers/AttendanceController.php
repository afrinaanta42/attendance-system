<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
class AttendanceController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $classes = ClassRoom::all();
        } else {
            $teacher = Auth::user()->teacher;

            if (!$teacher || !$teacher->class_id) {
                return view('attendance.no-class');
            }

            $classes = ClassRoom::where('id', $teacher->class_id)->get();
        }

        return view('attendance.index', compact('classes'));
    }

    public function fetchStudents(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
        ]);

        $existing = Attendance::where('class_id', $request->class_id)
            ->where('date', $request->date)->exists();

        if ($existing) {
            return response()->json(['error' => 'Attendance already taken for this date.'], 422);
        }

        $students = Student::where('class_id', $request->class_id)
            ->with('user')
            ->get();

        return response()->json([
            'students' => $students,
            'date' => $request->date,
            'class_id' => $request->class_id,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:Present,Absent',
        ]);

        $date = $request->date;
        $classId = $request->class_id;

        if (Attendance::where('class_id', $classId)->where('date', $date)->exists()) {
            return back()->with('error', 'Attendance already submitted for this date!');
        }

        // Get teacher safely
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            return back()->with('error', 'Teacher profile not found.');
        }

        foreach ($request->attendance as $item) {
            Attendance::create([
                'class_id' => $classId,
                'student_id' => $item['student_id'],
                'teacher_id' => $teacher->id,
                'date' => $date,
                'status' => $item['status'],
            ]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Marked Attendance',
            'details' => "Class: {$classId}, Date: {$date}, Records: " . count($request->attendance),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance submitted successfully!');
    }

    public function report(Request $request)
    {
        $query = Attendance::with(['student.user', 'classRoom']);

        if ($request->filled('month')) {
            $month = $request->month;
            $query->whereYear('date', substr($month, 0, 4))
                ->whereMonth('date', substr($month, 5, 2));
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        $classes = ClassRoom::all();
        $students = $request->class_id ? Student::where('class_id', $request->class_id)->with('user')->get() : collect();

        return view('attendance.report', compact('attendances', 'classes', 'students'));
    }

    public function myAttendance()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student profile not found.');
        }

        $attendances = Attendance::where('student_id', $student->id)
            ->with('classRoom')
            ->orderBy('date', 'desc')
            ->paginate(20);

        $total = $attendances->total();
        $present = Attendance::where('student_id', $student->id)->where('status', 'Present')->count();
        $percentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;

        return view('attendance.my', compact('attendances', 'present', 'total', 'percentage'));
    }

    public function export(Request $request)
    {
        $filename = 'attendance-report-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new AttendanceExport($request), $filename);
    }
}
