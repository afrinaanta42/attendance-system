@extends('layouts.app')

@section('title', 'Student Details')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Student Details</h1>
            <p class="mt-1 text-sm text-gray-600">View student information and records</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('students.edit', $student) }}"
                class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('students.index') }}"
                class="inline-flex items-center px-4 py-2 bg-attendance-blue/30 text-attendance-blue font-medium rounded-lg hover:bg-attendance-blue/50 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Students
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Student Profile Card -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex flex-col md:flex-row md:items-start md:space-x-6">
                <!-- Profile Icon -->
                <div class="mb-4 md:mb-0">
                    <div
                        class="w-20 h-20 bg-attendance-blue/10 rounded-full flex items-center justify-center mx-auto md:mx-0">
                        <i class="fas fa-user-graduate text-attendance-blue text-3xl"></i>
                    </div>
                </div>

                <!-- Student Information -->
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $student->user->name }}</h2>
                            <p class="text-sm text-gray-600">{{ $student->user->email }}</p>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="px-3 py-1 bg-primary/10 text-primary font-medium rounded-full text-sm">
                                Roll No: {{ $student->roll_number }}
                            </span>
                        </div>
                    </div>

                    <!-- Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <h3 class="text-sm font-medium text-gray-500">Class Information</h3>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-chalkboard text-primary text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        @if ($student->classRoom)
                                            {{ $student->classRoom->class_name }}
                                            @if ($student->classRoom->section)
                                                <span class="text-gray-600">({{ $student->classRoom->section }})</span>
                                            @endif
                                        @else
                                            Not assigned
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-sm font-medium text-gray-500">Parent Information</h3>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-attendance-blue/10 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-friends text-attendance-blue text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $student->parent_name ?: 'Not specified' }}</p>
                                    <p class="text-sm text-gray-600">{{ $student->parent_phone ?: 'No phone' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-sm font-medium text-gray-500">Account Status</h3>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-check-circle text-green-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Active</p>
                                    <p class="text-sm text-gray-600">Member since
                                        {{ $student->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Section -->
                    @if ($student->address)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Address</h3>
                            <p class="text-sm text-gray-900">{{ $student->address }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attendance Summary (Optional) -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Present</p>
                            <p class="text-2xl font-bold text-gray-900">85%</p>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Absent</p>
                            <p class="text-2xl font-bold text-gray-900">10%</p>
                        </div>
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-times text-yellow-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Late</p>
                            <p class="text-2xl font-bold text-gray-900">5%</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('students.edit', $student) }}"
                class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                Edit Student
            </a>
            <a href="{{ route('students.index') }}"
                class="px-6 py-2 bg-gradient-to-r from-primary to-primary/90 text-white font-medium rounded-lg hover:from-primary/90 hover:to-primary transition">
                Back to List
            </a>
        </div>
    </div>
@endsection
