<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $classes = ClassRoom::all();

            // Get attendance records with filters
            $query = Attendance::with(['student.user', 'teacher.user', 'classRoom'])
                ->latest();

            if ($request->has('class_id') && $request->class_id) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->has('date') && $request->date) {
                $query->whereDate('date', $request->date);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            $attendances = $query->paginate(20);

            // Get stats for today
            $todayStats = Attendance::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent
        ')
                ->whereDate('date', today())
                ->first();

            // Calculate attendance rate
            $attendanceRate = $todayStats->total > 0
                ? round(($todayStats->present / $todayStats->total) * 100, 2)
                : 0;

            // Get recent attendance by class for the last 5 days
            $recentAttendance = Attendance::selectRaw('
            class_id,
            DATE(date) as attendance_date,
            COUNT(*) as total,
            SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present_count,
            SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent_count
        ')
                ->with('classRoom')
                ->whereDate('date', '>=', now()->subDays(5))
                ->groupBy('class_id', 'attendance_date')
                ->orderBy('attendance_date', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'class_name' => $item->classRoom->class_name . ($item->classRoom->section ? ' (' . $item->classRoom->section . ')' : ''),
                        'date' => $item->attendance_date,
                        'present_count' => $item->present_count,
                        'absent_count' => $item->absent_count,
                        'attendance_rate' => $item->total > 0 ? round(($item->present_count / $item->total) * 100, 2) : 0,
                        'status' => $item->present_count > $item->absent_count ? 'Present' : 'Absent'
                    ];
                })
                ->take(5);

            $totalStudents = Student::count();

            return view('attendance.index', compact(
                'classes',
                'attendances',
                'todayStats',
                'attendanceRate',
                'recentAttendance',
                'totalStudents'
            ));
        } elseif ($user->isTeacher()) {
            // Existing teacher logic
            $teacher = $user->teacher;
            $classes = $teacher ? ClassRoom::where('id', $teacher->class_id)->get() : collect();

            return view('attendance.mark', compact('classes')); // Create a separate view for teachers
        } else {
            abort(403, 'Unauthorized');
        }
    }

    // Add this method for fetching single attendance record
    public function show(Attendance $attendance)
    {
        return response()->json([
            'status' => $attendance->status,
            'remarks' => $attendance->remarks
        ]);
    }

    // Add this method for updating attendance
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:Present,Absent',
            'remarks' => 'nullable|string|max:500',
        ]);

        $attendance->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance updated successfully.');
    }

    // Add this method for deleting attendance
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record deleted successfully.');
    }
}
