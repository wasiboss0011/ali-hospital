<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->isDoctor()) {
            $doctor = $user->doctor;
            $todayAppointments = $doctor
                ? Appointment::with(['patient', 'department'])
                    ->where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', today())
                    ->orderByRaw("FIELD(priority, 'emergency', 'urgent', 'routine')")
                    ->orderBy('token_number')
                    ->get()
                : collect();

            $pastAppointments = $doctor
                ? Appointment::with(['patient', 'department'])
                    ->where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', '<', today())
                    ->latest('appointment_date')
                    ->take(10)
                    ->get()
                : collect();

            return view('dashboard.doctor', compact('doctor', 'todayAppointments', 'pastAppointments'));
        }

        $stats = [
            'total_patients' => Patient::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'total_doctors' => Doctor::where('is_available', true)->count(),
            'pending_appointments' => Appointment::whereDate('appointment_date', today())->where('status', 'scheduled')->count(),
        ];

        $todayAppointments = Appointment::with(['patient', 'doctor.user', 'department'])
            ->whereDate('appointment_date', today())
            ->orderByRaw("FIELD(priority, 'emergency', 'urgent', 'routine')")
            ->orderBy('token_number')
            ->get();

        $recentPatients = Patient::latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'todayAppointments', 'recentPatients'));
    }
}
