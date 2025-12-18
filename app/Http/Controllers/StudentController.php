<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'class'])->latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roll_number' => 'required|string|max:50|unique:students',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'class_id' => 'required|exists:classes,id',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign student role
        $studentRole = Role::where('name', 'Student')->first();
        $user->roles()->attach($studentRole);

        // Create student record
        Student::create([
            'user_id' => $user->id,
            'roll_number' => $request->roll_number,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::all();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'roll_number' => 'required|string|max:50|unique:students,roll_number,' . $student->id,
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'class_id' => 'required|exists:classes,id',
        ]);

        // Update user
        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update student record
        $student->update([
            'roll_number' => $request->roll_number,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }
    
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function destroy(Student $student)
    {
        // Delete user (which will cascade to student record)
        $student->user->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
