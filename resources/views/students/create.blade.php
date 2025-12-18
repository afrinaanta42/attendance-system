@extends('layouts.app')

@section('title', 'Create New Student')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Student</h1>
            <p class="mt-1 text-sm text-gray-600">Add a new student to the system</p>
        </div>
        <div>
            <a href="{{ route('students.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Students
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Student Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-attendance-red">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('name') border-attendance-red @enderror"
                            placeholder="Enter student's full name">
                        @error('name')
                            <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address <span class="text-attendance-red">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('email') border-attendance-red @enderror"
                            placeholder="student@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password <span class="text-attendance-red">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('password') border-attendance-red @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm Password <span class="text-attendance-red">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                    </div>
                </div>

                <!-- Student Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="roll_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Roll Number <span class="text-attendance-red">*</span>
                        </label>
                        <input type="text" id="roll_number" name="roll_number" value="{{ old('roll_number') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('roll_number') border-attendance-red @enderror"
                            placeholder="Enter roll number">
                        @error('roll_number')
                            <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Class <span class="text-attendance-red">*</span>
                        </label>
                        <select id="class_id" name="class_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('class_id') border-attendance-red @enderror">
                            <option value="">Select a class...</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }} {{ $class->section ? '(' . $class->section . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Parent/Guardian Name
                        </label>
                        <input type="text" id="parent_name" name="parent_name" value="{{ old('parent_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('parent_name') border-attendance-red @enderror"
                            placeholder="Enter parent/guardian name">
                        @error('parent_name')
                            <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Parent/Guardian Phone
                        </label>
                        <input type="tel" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('parent_phone') border-attendance-red @enderror"
                            placeholder="Enter phone number">
                        @error('parent_phone')
                            <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Address
                    </label>
                    <textarea id="address" name="address" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('address') border-attendance-red @enderror"
                        placeholder="Enter student's address">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('students.index') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-primary to-primary/90 text-white font-medium rounded-lg hover:from-primary/90 hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        Create Student
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
