{{-- resources/views/attendance/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Mark Attendance</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Select Class</label>
                    <select id="classSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Choose class...</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->class_name }}
                                {{ $class->section ? '- ' . $class->section : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" id="attendanceDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        max="{{ date('Y-m-d') }}">
                </div>

                <div class="flex items-end">
                    <button id="loadStudents" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Load
                        Students</button>
                </div>
            </div>

            <form id="attendanceForm" action="{{ route('attendance.store') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="class_id" id="formClassId">
                <input type="hidden" name="date" id="formDate">

                <div id="studentsTable"></div>

                <div class="mt-6 text-right">
                    <button type="submit"
                        class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 font-semibold">
                        Submit Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loadStudents').addEventListener('click', function() {
            const classId = document.getElementById('classSelect').value;
            const date = document.getElementById('attendanceDate').value;

            if (!classId || !date) {
                alert('Please select both class and date');
                return;
            }

            fetch("Loading students...");

            fetch("{{ url('/attendance/fetch-students') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        class_id: classId,
                        date: date
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    document.getElementById('formClassId').value = data.class_id;
                    document.getElementById('formDate').value = data.date;
                    document.getElementById('attendanceForm').classList.remove('hidden');

                    let table = `
            <table class="min-w-full table-auto border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Roll No</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-center">Present</th>
                        <th class="px-4 py-3 text-center">Absent</th>
                    </tr>
                </thead>
                <tbody>`;

                    data.students.forEach(student => {
                        table += `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">${student.roll_number}</td>
                    <td class="px-4 py-3">${student.user.name}</td>
                    <td class="px-4 py-3 text-center">
                        <input type="radio" name="attendance[${student.id}][status]" value="Present" required>
                        <input type="hidden" name="attendance[${student.id}][student_id]" value="${student.id}">
                    </td>
                    <td class="px-4 py-3 text-center">
                        <input type="radio" name="attendance[${student.id}][status]" value="Absent">
                    </td>
                </tr>`;
                    });

                    table += `</tbody></table>`;
                    document.getElementById('studentsTable').innerHTML = table;
                })
                .catch(err => alert('Error loading students'));
        });
    </script>
@endsection
