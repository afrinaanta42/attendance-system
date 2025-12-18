<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::with(['teacher.user', 'students'])->latest()->paginate(10);
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
            'subject' => 'nullable|string|max:255',
        ]);

        ClassRoom::create($request->only(['class_name', 'section', 'subject']));

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function edit(ClassRoom $class)
    {
        // dd($class);
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
        ]);

        $class->update($request->only(['class_name', 'section', 'subject']));

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassRoom $class)
    {
        $class->delete();
        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}
