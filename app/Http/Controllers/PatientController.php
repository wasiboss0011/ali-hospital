<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', '%' . $s . '%')
                  ->orWhere('uhid', 'like', '%' . $s . '%')
                  ->orWhere('phone', 'like', '%' . $s . '%');
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:20',
            'age' => 'nullable|integer|min:0|max:130',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:5',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        $patient = DB::transaction(function () use ($validated) {
            $year = date('Y');
            $lastPatient = Patient::where('uhid', 'like', 'AH-' . $year . '-%')
                ->lockForUpdate()
                ->latest('id')
                ->first();

            $nextNumber = 1;
            if ($lastPatient && preg_match('/AH-\d{4}-(\d+)/', $lastPatient->uhid, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            }
            $validated['uhid'] = sprintf('AH-%s-%04d', $year, $nextNumber);
            $validated['age'] = $validated['age'] ?? 0;
            $validated['address'] = $validated['address'] ?? 'Campbell Road, Mozzam Nagar, Lucknow 226003';
            $validated['emergency_contact_name'] = $validated['emergency_contact'] ?? null;

            return Patient::create($validated);
        });

        return redirect()->route('patients.index')->with('success', 'Patient ' . $patient->name . ' registered successfully with UHID: ' . $patient->uhid);
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments.doctor.user', 'appointments.department']);
        return view('patients.show', compact('patient'));
    }
}
