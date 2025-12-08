@extends('layouts.app')
@section('page-title', 'My Attendance')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-3xl font-bold text-green-600">{{ $present }}</h3>
            <p class="text-gray-600">Present Days</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-3xl font-bold text-red-600">{{ $total - $present }}</h3>
            <p class="text-gray-600">Absent Days</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-4xl font-bold text-indigo-600">{{ $percentage }}%</h3>
            <p class="text-gray-600">Attendance Rate</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left">Date</th>
                    <th class="px-6 py-4 text-left">Class</th>
                    <th class="px-6 py-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($attendances as $a)
                    <tr>
                        <td class="px-6 py-4">{{ $a->date->format('d M Y') }}</td>
                        <td class="px-6 py-4">{{ $a->classRoom->class_name }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-4 py-1 rounded-full text-sm {{ $a->status == 'Present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $a->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500">No attendance recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $attendances->links() }}</div>
    </div>
@endsection
