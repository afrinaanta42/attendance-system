@extends('layouts.app')
@section('page-title', 'Attendance Report')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-2xl font-bold mb-6">Attendance Report</h3>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div>
                <label class="block text-sm font-medium">Month</label>
                <input type="month" name="month" value="{{ request('month') }}"
                    class="mt-1 block w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium">Class</label>
                <select name="class_id" class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="">All Classes</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }} {{ $class->section }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Student</label>
                <select name="student_id" class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="">All Students</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->roll_number }} - {{ $student->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                    Filter
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendances as $a)
                        <tr>
                            <td class="px-6 py-4">{{ $a->date->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $a->student->user->name }} ({{ $a->student->roll_number }})</td>
                            <td class="px-6 py-4">{{ $a->classRoom->class_name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-medium {{ $a->status == 'Present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $a->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $attendances->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
