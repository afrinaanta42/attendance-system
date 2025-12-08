<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'classRoom'])->paginate(15);
        return view('teachers.index', compact('teachers'));
    }
    public function create()
    {
        $classes = ClassRoom::all();
        return view('teachers.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'class_id' => 'nullable|exists:classes,id',
            'subject' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Attach the Teacher role if using a roles system
        $teacherRole = Role::where('name', 'Teacher')->first();
        if ($teacherRole) {
            $user->roles()->attach($teacherRole);
        }

        Teacher::create([
            'user_id' => $user->id,
            'class_id' => $request->class_id,
            'subject' => $request->subject,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher added!');
    }

    public function edit(Teacher $teacher)
    {
        $classes = ClassRoom::all();
        return view('teachers.edit', compact('teacher', 'classes'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $teacher->user->id,
            'class_id' => 'nullable|exists:classes,id',
            'subject' => 'nullable|string',
        ]);

        // Update related user
        $teacher->user->update($request->only('name', 'email'));

        // Update teacher-specific fields
        $teacher->update($request->only('class_id', 'subject'));

        return redirect()->route('teachers.index')->with('success', 'Teacher updated!');
    }
}
