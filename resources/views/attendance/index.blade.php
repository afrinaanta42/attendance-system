@extends('layouts.app')

@section('title', 'Attendance Management')

@section('header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Attendance Management</h1>
            <p class="mt-1 text-sm text-gray-600">View and manage attendance records</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('attendance.report') }}"
                class="inline-flex items-center px-4 py-2 bg-attendance-blue/10 text-attendance-blue font-medium rounded-lg hover:bg-attendance-blue/20 transition">
                <i class="fas fa-chart-bar mr-2"></i> View Reports
            </a>
            <a href="{{ route('attendance.daily.report') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary to-primary/90 text-white font-medium rounded-lg hover:from-primary/90 hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                <i class="fas fa-plus mr-2"></i> Mark Attendance
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-attendance-green/10 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user-check text-attendance-green"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Present Today</p>
                        <p class="text-lg font-bold text-gray-900">{{ $todayStats['present'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-attendance-red/10 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user-times text-attendance-red"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Absent Today</p>
                        <p class="text-lg font-bold text-gray-900">{{ $todayStats['absent'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-attendance-blue/10 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-users text-attendance-blue"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Students</p>
                        <p class="text-lg font-bold text-gray-900">{{ $totalStudents ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-percentage text-primary"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Overall Rate</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $todayStats['attendance_rate'] ?? 0 }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow p-6">
            <form method="GET" action="{{ route('attendance.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Class
                        </label>
                        <select id="class_id" name="class_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                            <option value="">All Classes</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }} {{ $class->section ? '(' . $class->section . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                            Date
                        </label>
                        <input type="date" id="date" name="date"
                            value="{{ request('date', now()->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>
                        <select id="status" name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                            <option value="">All Status</option>
                            <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                            <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full px-4 py-3 bg-gradient-to-r from-primary to-primary/90 text-white font-medium rounded-lg hover:from-primary/90 hover:to-primary transition">
                            <i class="fas fa-filter mr-2"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Attendance Records -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            @if ($attendances->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No attendance records found</h3>
                    <p class="text-gray-500">Try adjusting your filters or mark attendance for today.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Class
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Teacher
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Remarks
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($attendances as $attendance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $attendance->date->format('M d, Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $attendance->date->format('l') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-attendance-blue/10 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user-graduate text-attendance-blue text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $attendance->student->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Roll No: {{ $attendance->student->roll_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $attendance->classRoom->class_name }}
                                            @if ($attendance->classRoom->section)
                                                <span class="text-gray-500">({{ $attendance->classRoom->section }})</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($attendance->status == 'Present')
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-attendance-green/10 text-attendance-green">
                                                <i class="fas fa-check-circle mr-1"></i> Present
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-attendance-red/10 text-attendance-red">
                                                <i class="fas fa-times-circle mr-1"></i> Absent
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if ($attendance->teacher)
                                                {{ $attendance->teacher->user->name }}
                                            @else
                                                <span class="text-gray-400">Not assigned</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            {{ $attendance->remarks ?? 'No remarks' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="editAttendance({{ $attendance->id }})"
                                                class="text-primary hover:text-primary/80 transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('attendance.destroy', $attendance) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this attendance record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-attendance-red hover:text-attendance-red/80 transition-colors"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($attendances->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $attendances->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Recent Attendance Summary -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Attendance Summary</h3>
            <div class="space-y-4">
                @foreach ($recentAttendance as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-lg flex items-center justify-center mr-3
                                {{ $item['status'] == 'Present' ? 'bg-attendance-green/10' : 'bg-attendance-red/10' }}">
                                <i
                                    class="fas {{ $item['status'] == 'Present' ? 'fa-user-check text-attendance-green' : 'fa-user-times text-attendance-red' }}"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $item['class_name'] }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $item['date'] }} • {{ $item['present_count'] }} present,
                                    {{ $item['absent_count'] }} absent
                                </div>
                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $item['attendance_rate'] }}%
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4">
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Edit Attendance Record</h3>
                    <p class="text-sm text-gray-600 mt-1">Update student attendance status</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="Present"
                                    class="text-attendance-green focus:ring-attendance-green">
                                <span class="ml-2 text-sm text-gray-700">Present</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="Absent"
                                    class="text-attendance-red focus:ring-attendance-red">
                                <span class="ml-2 text-sm text-gray-700">Absent</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                            Remarks
                        </label>
                        <textarea id="remarks" name="remarks" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                            placeholder="Enter remarks (optional)"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-primary to-primary/90 text-white font-medium rounded-lg hover:from-primary/90 hover:to-primary transition">
                        Update Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editAttendance(id) {
            fetch(`/attendance/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#editForm [name="status"][value="' + data.status + '"]').checked = true;
                    document.querySelector('#editForm #remarks').value = data.remarks || '';
                    document.querySelector('#editForm').action = `/attendance/${id}`;
                    document.getElementById('editModal').classList.remove('hidden');
                })
                .catch(error => console.error('Error:', error));
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal on outside click
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection
