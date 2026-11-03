<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Department;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'department']);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->whereHas('user', function($uq) use ($s) {
                    $uq->where('name', 'like', '%' . $s . '%');
                })->orWhere('specialization', 'like', '%' . $s . '%');
            });
        }

        $doctors = $query->paginate(10)->withQueryString();
        $departments = Department::where('is_active', true)->get();

        return view('doctors.index', compact('doctors', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('doctors.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'specialization' => 'required|string',
            'qualification' => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'room_number' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'doctor',
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'specialization' => $validated['specialization'],
            'qualification' => $validated['qualification'] ?? 'MBBS, MD',
            'registration_number' => 'DOC-' . strtoupper(substr(uniqid(), 7)),
            'consultation_fee' => $validated['consultation_fee'],
            'room_number' => $validated['room_number'] ?? 'OPD 1',
            'is_available' => true,
        ]);

        return redirect()->route('doctors.index')->with('success', 'Dr. ' . $validated['name'] . ' onboarded successfully!');
    }
}
