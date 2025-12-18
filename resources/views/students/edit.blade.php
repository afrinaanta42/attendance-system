@extends('layouts.app')

@section('title', 'Edit Student')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Student</h1>
            <p class="mt-1 text-sm text-gray-600">Update student information</p>
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
        <form action="{{ route('students.update', $student) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Student Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-attendance-red">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $student->user->name) }}"
                            required
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
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $student->user->email) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('email') border-attendance-red @enderror"
                            placeholder="student@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Student Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="roll_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Roll Number <span class="text-attendance-red">*</span>
                        </label>
                        <input type="text" id="roll_number" name="roll_number"
                            value="{{ old('roll_number', $student->roll_number) }}" required
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
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
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
                        <input type="text" id="parent_name" name="parent_name"
                            value="{{ old('parent_name', $student->parent_name) }}"
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
                        <input type="tel" id="parent_phone" name="parent_phone"
                            value="{{ old('parent_phone', $student->parent_phone) }}"
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
                        placeholder="Enter student's address">{{ old('address', $student->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Student Information (Read-only) -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Current Student Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500">Name</p>
                            <p class="text-sm font-medium text-gray-900">{{ $student->user->name }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500">Roll Number</p>
                            <p class="text-sm font-medium text-gray-900">{{ $student->roll_number }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500">Class</p>
                            <p class="text-sm font-medium text-gray-900">
                                @if ($student->classRoom)
                                    {{ $student->classRoom->class_name }}
                                    {{ $student->classRoom->section ? '(' . $student->classRoom->section . ')' : '' }}
                                @else
                                    Not assigned
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Password Update Section (Optional) -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Password Update</h3>
                            <p class="text-sm text-gray-600">Leave blank if you don't want to change the password</p>
                        </div>
                        <button type="button" id="togglePasswordFields"
                            class="px-4 py-2 text-sm font-medium text-primary hover:text-primary/80 transition-colors">
                            <i class="fas fa-lock mr-1"></i> Change Password
                        </button>
                    </div>

                    <div id="passwordFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                                New Password
                            </label>
                            <input type="password" id="new_password" name="new_password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('new_password') border-attendance-red @enderror">
                            @error('new_password')
                                <p class="mt-1 text-sm text-attendance-red">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm New Password
                            </label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex space-x-2">
                        <a href="{{ route('students.index') }}"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="button"
                            onclick="if(confirm('Are you sure you want to delete this student?')) { document.getElementById('delete-form').submit(); }"
                            class="px-6 py-2 bg-attendance-red text-white font-medium rounded-lg hover:bg-attendance-red/90 transition">
                            Delete Student
                        </button>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-primary to-primary/90 text-white font-medium rounded-lg hover:from-primary/90 hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                            Update Student
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Hidden Delete Form -->
        <form id="delete-form" action="{{ route('students.destroy', $student) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('togglePasswordFields');
            const passwordFields = document.getElementById('passwordFields');

            toggleButton.addEventListener('click', function() {
                passwordFields.classList.toggle('hidden');

                if (passwordFields.classList.contains('hidden')) {
                    toggleButton.innerHTML = '<i class="fas fa-lock mr-1"></i> Change Password';
                    // Clear password fields when hiding
                    document.getElementById('new_password').value = '';
                    document.getElementById('new_password_confirmation').value = '';
                } else {
                    toggleButton.innerHTML = '<i class="fas fa-times mr-1"></i> Cancel Password Change';
                }
            });
        });
    </script>
@endpush
