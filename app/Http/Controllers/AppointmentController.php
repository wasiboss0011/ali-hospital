<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Department;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Appointment::with(['patient', 'doctor.user', 'department']);

        if ($user && $user->isDoctor() && $user->doctor) {
            $query->where('doctor_id', $user->doctor->id);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('patient', function($q) use ($s) {
                $q->where('name', 'like', '%' . $s . '%')
                  ->orWhere('uhid', 'like', '%' . $s . '%');
            });
        }

        $appointments = $query->latest('appointment_date')->orderBy('token_number')->paginate(10)->withQueryString();
        $doctors = Doctor::with('user')->where('is_available', true)->get();
        $departments = Department::where('is_active', true)->get();

        return view('appointments.index', compact('appointments', 'doctors', 'departments'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $doctors = Doctor::with(['user', 'department'])->where('is_available', true)->get();
        $departments = Department::where('is_active', true)->get();

        return view('appointments.create', compact('patients', 'doctors', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable',
            'priority' => 'required|in:routine,urgent,emergency',
            'notes' => 'nullable|string',
        ]);

        $maxToken = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->max('token_number') ?? 0;

        $validated['token_number'] = $maxToken + 1;
        $validated['appointment_time'] = $validated['appointment_time'] ?? '10:00:00';
        $validated['status'] = 'scheduled';

        Appointment::create($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully! Assigned Token #' . $validated['token_number']);
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,confirmed,in_consultation,completed,cancelled',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return back()->with('success', 'Appointment status updated.');
    }
}
