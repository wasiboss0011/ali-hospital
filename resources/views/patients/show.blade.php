@extends('layouts.app')

@section('title', 'Patient File - ' . $patient->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('patients.index') }}" class="text-decoration-none text-muted small">
        &larr; Back to Patient Registry
    </a>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div>
            <h3 class="fw-bold mb-1 text-dark">{{ $patient->name }}</h3>
            <span class="badge bg-dark fs-6 font-monospace me-2">{{ $patient->uhid }}</span>
            <span class="badge bg-danger text-uppercase">{{ $patient->blood_group ?? 'Blood Group Unlisted' }}</span>
        </div>
        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-hospital-red">
            <i class="bi bi-calendar-plus me-1"></i> Book New OPD Visit
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Patient Profile Summary -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white fw-semibold">
                <i class="bi bi-person-circle me-2 text-danger"></i>Demographic Details
            </div>
            <div class="card-body p-3">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted fw-bold" style="width: 40%;">Age / Gender:</td>
                        <td>{{ $patient->age ? $patient->age . ' yrs' : 'N/A' }} • {{ ucfirst($patient->gender) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Date of Birth:</td>
                        <td>{{ $patient->date_of_birth ? date('d M Y', strtotime($patient->date_of_birth)) : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Phone:</td>
                        <td><a href="tel:{{ $patient->phone }}" class="text-decoration-none">{{ $patient->phone }}</a></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Emergency:</td>
                        <td>{{ $patient->emergency_contact ?? 'None listed' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Address:</td>
                        <td>{{ $patient->address ?? 'Campbell Road, Lucknow' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Registered:</td>
                        <td>{{ $patient->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-danger text-white fw-semibold">
                <i class="bi bi-shield-exclamation me-2"></i>Medical History & Alerts
            </div>
            <div class="card-body p-3">
                <p class="mb-0 text-dark small">
                    {{ $patient->medical_history ?? 'No allergies or chronic alerts documented for this patient.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Encounters & Medical History Timeline -->
    <div class="col-lg-8">
        <div class="card card-table">
            <div class="card-header card-header-navy d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Clinical Encounters & Consultation History</span>
                <span class="badge bg-light text-dark">{{ $patient->appointments->count() }} Visits</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Date / Token</th>
                            <th>Doctor / Dept</th>
                            <th>Diagnosis & Symptoms</th>
                            <th>Prescription (Rx)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patient->appointments as $app)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ date('d M Y', strtotime($app->appointment_date)) }}</div>
                                <span class="badge bg-secondary font-monospace">Token #{{ $app->token_number }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-primary">{{ $app->doctor->name }}</div>
                                <small class="text-muted">{{ $app->department->name }}</small>
                            </td>
                            <td>
                                @if($app->diagnosis)
                                    <span class="badge bg-light text-dark border d-block mb-1">{{ $app->diagnosis }}</span>
                                @endif
                                <small class="text-muted">{{ $app->notes ?? 'Routine OPD Consultation' }}</small>
                            </td>
                            <td>
                                @if($app->prescription)
                                    <pre class="bg-light p-2 rounded small mb-0 font-monospace text-wrap" style="max-width: 250px;">{{ $app->prescription }}</pre>
                                @else
                                    <span class="text-muted small">No Rx recorded</span>
                                @endif
                            </td>
                            <td>
                                @if($app->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($app->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-warning text-dark">Scheduled</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-event fs-2 text-secondary d-block mb-2"></i>
                                No clinical appointments recorded for this patient yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
