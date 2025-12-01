<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'classRoom'])->paginate(15);
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'roll_number' => 'required|unique:students',
            'class_id' => 'nullable|exists:classes,id',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach(Role::where('name', 'Student')->first());

        Student::create([
            'user_id' => $user->id,
            'roll_number' => $request->roll_number,
            'class_id' => $request->class_id,
            'dob' => $request->dob,
            'gender' => $request->gender,
        ]);

        return redirect()->route('students.index')->with('success', 'Student added successfully!');
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::all();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $student->user->id,
            'roll_number' => 'required|unique:students,roll_number,' . $student->id,
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $student->update($request->only(['roll_number', 'class_id', 'dob', 'gender']));

        return redirect()->route('students.index')->with('success', 'Student updated!');
    }

    public function destroy(Student $student)
    {
        $student->user->delete(); // cascades to student
        return back()->with('success', 'Student deleted.');
    }
}
