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
        $teachers = Teacher::with(['user', 'classRoom'])->latest()->paginate(10);
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'subject' => 'nullable|string|max:255',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign teacher role
        $teacherRole = Role::where('name', 'Teacher')->first();
        $user->roles()->attach($teacherRole);

        // Create teacher record
        Teacher::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'class_id' => $request->class_id,
        ]);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        $classes = ClassRoom::all();
        return view('teachers.edit', compact('teacher', 'classes'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'subject' => 'nullable|string|max:255',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        // Update user
        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update teacher record
        $teacher->update([
            'subject' => $request->subject,
            'class_id' => $request->class_id,
        ]);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        // Delete user (which will cascade to teacher record)
        $teacher->user->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
