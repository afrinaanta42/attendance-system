<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::all();
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:100',
        ]);

        ClassRoom::create($request->all());
        return redirect()->route('classes.index')->with('success', 'Class created successfully!');
    }

    public function edit(ClassRoom $class)
    {
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:100',
        ]);

        $class->update($request->all());
        return redirect()->route('classes.index')->with('success', 'Class updated successfully!');
    }

    public function destroy(ClassRoom $class)
    {
        // Check if class has students or teacher before deleting
        if ($class->students()->count() > 0 || $class->teacher()->exists()) {
            return back()->with('error', 'Cannot delete class with associated students or teacher!');
        }

        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully!');
    }
}
