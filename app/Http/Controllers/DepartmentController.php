<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('doctors.user')->where('is_active', true)->get();
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $validated['is_active'] = true;
        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department ' . $validated['name'] . ' created successfully!');
    }
}
