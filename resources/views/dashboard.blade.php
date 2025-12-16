@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-primary to-primary/90 rounded-xl p-6 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-white/80">
                        @if (auth()->user()->isAdmin())
                            You have full administrative control over the attendance system.
                        @elseif(auth()->user()->isTeacher())
                            Manage your class attendance and track student progress.
                        @elseif(auth()->user()->isStudent())
                            Track your attendance and academic progress.
                        @endif
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="inline-flex items-center px-4 py-2 bg-white/20 rounded-lg">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role-Based Statistics -->
        @if (auth()->user()->isAdmin())
            <!-- Admin Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Students</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Student::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-graduate text-primary text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Teachers</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Teacher::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-tie text-accent text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Classes</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\ClassRoom::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-attendance-blue/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-attendance-blue text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Today's Attendance</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ \App\Models\Attendance::whereDate('date', today())->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-attendance-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-attendance-green text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions for Admin -->
            <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('classes.create') }}"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-primary/30 transition-colors group">
                        <div
                            class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-primary/20">
                            <i class="fas fa-plus text-primary"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 group-hover:text-primary">Add New Class</p>
                            <p class="text-sm text-gray-500">Create a new class</p>
                        </div>
                    </a>
                    <a href="{{ route('teachers.create') }}"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-accent/30 transition-colors group">
                        <div
                            class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-accent/20">
                            <i class="fas fa-plus text-accent"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 group-hover:text-accent">Add Teacher</p>
                            <p class="text-sm text-gray-500">Register new teacher</p>
                        </div>
                    </a>
                    <a href="{{ route('attendance.report') }}"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-attendance-blue/30 transition-colors group">
                        <div
                            class="w-10 h-10 bg-attendance-blue/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-attendance-blue/20">
                            <i class="fas fa-chart-bar text-attendance-blue"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 group-hover:text-attendance-blue">View Reports</p>
                            <p class="text-sm text-gray-500">Attendance analytics</p>
                        </div>
                    </a>
                </div>
            </div>
        @elseif(auth()->user()->isTeacher())
            <!-- Teacher Dashboard -->
            @php
                $teacher = auth()->user()->teacher;
                $class = $teacher ? $teacher->classRoom : null;
                $todayAttendance = $class
                    ? \App\Models\Attendance::where('class_id', $class->id)->whereDate('date', today())->count()
                    : 0;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Class Info -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">My Class</h2>
                    @if ($class)
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-chalkboard-teacher text-primary"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $class->class_name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $class->section ? 'Section: ' . $class->section : '' }}</p>
                                    @if ($class->subject)
                                        <p class="text-sm text-gray-500">{{ $class->subject }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-gray-500 text-sm">Total Students: <span
                                        class="font-semibold">{{ $class->students()->count() }}</span></p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">No class assigned yet.</p>
                    @endif
                </div>

                <!-- Today's Attendance -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Today's Attendance</h2>
                    <div class="text-center py-8">
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 bg-attendance-green/10 rounded-full mb-4">
                            <i class="fas fa-clipboard-check text-attendance-green text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $todayAttendance }}</p>
                        <p class="text-gray-500 mt-2">Students marked today</p>
                        @if ($todayAttendance == 0)
                            <a href="{{ route('attendance.index') }}"
                                class="inline-block mt-4 px-6 py-2 bg-gradient-to-r from-primary to-primary/90 text-white rounded-lg hover:from-primary/90 hover:to-primary transition">
                                Mark Attendance
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Teacher Quick Actions -->
            <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('attendance.index') }}"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-primary/30 transition-colors group">
                        <div
                            class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-primary/20">
                            <i class="fas fa-calendar-check text-primary"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 group-hover:text-primary">Mark Attendance</p>
                            <p class="text-sm text-gray-500">Take today's attendance</p>
                        </div>
                    </a>
                    <a href="{{ route('attendance.report') }}"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-attendance-green/30 transition-colors group">
                        <div
                            class="w-10 h-10 bg-attendance-green/10 rounded-lg flex items-center justify-center mr-4 group-hover:bg-attendance-green/20">
                            <i class="fas fa-chart-bar text-attendance-green"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 group-hover:text-attendance-green">View Reports</p>
                            <p class="text-sm text-gray-500">Attendance analytics</p>
                        </div>
                    </a>
                </div>
            </div>
        @elseif(auth()->user()->isStudent())
            <!-- Student Dashboard -->
            @php
                $student = auth()->user()->student;
                $totalAttendance = $student ? $student->attendances()->count() : 0;
                $presentCount = $student ? $student->attendances()->where('status', 'Present')->count() : 0;
                $attendancePercentage = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 0;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Attendance Stats -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Attendance Summary</h2>
                    <div class="space-y-6">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center relative">
                                <svg class="w-32 h-32" viewBox="0 0 36 36">
                                    <path d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#E5E7EB"
                                        stroke-width="3" />
                                    <path d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                                        stroke="{{ $attendancePercentage >= 75 ? '#10B981' : '#EF4444' }}"
                                        stroke-width="3" stroke-dasharray="{{ $attendancePercentage }}, 100" />
                                    <text x="18" y="20.5" text-anchor="middle"
                                        fill="{{ $attendancePercentage >= 75 ? '#10B981' : '#EF4444' }}" font-size="8"
                                        font-weight="bold">{{ $attendancePercentage }}%</text>
                                </svg>
                            </div>
                            <p class="mt-4 text-gray-500">Overall Attendance Rate</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div
                                class="text-center p-4 bg-attendance-green/10 rounded-lg border border-attendance-green/20">
                                <p class="text-2xl font-bold text-attendance-green">{{ $presentCount }}</p>
                                <p class="text-sm text-attendance-green">Present</p>
                            </div>
                            <div class="text-center p-4 bg-attendance-red/10 rounded-lg border border-attendance-red/20">
                                <p class="text-2xl font-bold text-attendance-red">{{ $totalAttendance - $presentCount }}
                                </p>
                                <p class="text-sm text-attendance-red">Absent</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Class Info -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">My Information</h2>
                    <div class="space-y-4">
                        @if ($student)
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-user-graduate text-primary"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-sm text-gray-500">Roll No: {{ $student->roll_number }}</p>
                                </div>
                            </div>

                            @if ($student->class)
                                <div class="p-4 bg-primary/5 rounded-lg border border-primary/10">
                                    <p class="text-sm font-medium text-gray-900">Class: {{ $student->class->class_name }}
                                    </p>
                                    @if ($student->class->section)
                                        <p class="text-sm text-gray-500 mt-1">Section: {{ $student->class->section }}</p>
                                    @endif
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500">Student profile not found.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Recent Attendance</h2>
                    <a href="{{ route('attendance.my') }}"
                        class="text-primary hover:text-primary/80 text-sm font-medium group">
                        View All <i class="fas fa-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                @if ($student && $student->attendances()->count() > 0)
                    <div class="space-y-3">
                        @foreach ($student->attendances()->latest()->take(5)->get() as $attendance)
                            <div
                                class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-primary/30 transition-colors">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $attendance->date->format('F j, Y') }}</p>
                                    <p class="text-sm text-gray-500">Class:
                                        {{ $attendance->classRoom->class_name ?? 'N/A' }}</p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-medium 
                                {{ $attendance->status == 'Present' ? 'bg-attendance-green/10 text-attendance-green border border-attendance-green/20' : 'bg-attendance-red/10 text-attendance-red border border-attendance-red/20' }}">
                                    {{ $attendance->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No attendance records found.</p>
                @endif
            </div>
        @endif
    </div>
@endsection
