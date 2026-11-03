@extends('layouts.app')

@section('title', 'Doctor Consultation Console')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">Welcome, Dr. {{ $doctor ? $doctor->name : Auth::user()->name }}</h3>
        <p class="text-muted small mb-0">
            <span class="badge bg-primary me-2">{{ $doctor && $doctor->department ? $doctor->department->name : 'Clinical Staff' }}</span>
            Cabin / Room: {{ $doctor ? $doctor->room_number : 'OPD 1' }} • Specialized in {{ $doctor ? $doctor->specialization : 'General Medicine' }}
        </p>
    </div>
    <span class="badge bg-success py-2 px-3 fs-6">
        <i class="bi bi-circle-fill text-white me-1" style="font-size: 0.6rem;"></i> Consultation Active
    </span>
</div>

<!-- Today's Patient Queue for this Doctor -->
<div class="card card-table mb-4">
    <div class="card-header card-header-navy d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person-lines-fill me-2"></i>My Consultation Queue for Today ({{ now()->format('D, d M Y') }})</span>
        <span class="badge bg-danger">{{ $todayAppointments->where('status', 'scheduled')->count() }} Waiting</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>Token</th>
                    <th>Patient UHID & Name</th>
                    <th>Age / Gender</th>
                    <th>Priority</th>
                    <th>Symptoms / Reason</th>
                    <th>Status</th>
                    <th class="text-end">Clinical Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($todayAppointments as $app)
                <tr>
                    <td>
                        <span class="badge bg-dark rounded-pill px-3 py-2 fs-6">#{{ $app->token_number }}</span>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $app->patient->name }}</div>
                        <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ $app->patient->uhid }}</span>
                    </td>
                    <td>{{ $app->patient->age ?? 'N/A' }} yrs • {{ ucfirst($app->patient->gender) }}</td>
                    <td>
                        @if($app->priority === 'emergency')
                            <span class="badge bg-danger text-uppercase">Emergency</span>
                        @elseif($app->priority === 'urgent')
                            <span class="badge bg-warning text-dark text-uppercase">Urgent</span>
                        @else
                            <span class="badge bg-secondary text-uppercase">Routine</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $app->notes ?? 'Routine Consultation' }}</small></td>
                    <td>
                        @if($app->status === 'completed')
                            <span class="badge bg-success">Consulted</span>
                        @elseif($app->status === 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-warning text-dark">In Queue</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-hospital-primary" data-bs-toggle="modal" data-bs-target="#consultModal{{ $app->id }}">
                            <i class="bi bi-pencil-square me-1"></i> Consult / Rx
                        </button>

                        <!-- Modal for Clinical Notes & Prescription -->
                        <div class="modal fade" id="consultModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg text-start">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('appointments.status', $app->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-file-earmark-medical me-2 text-danger"></i>
                                                OPD Consultation File: {{ $app->patient->name }} ({{ $app->patient->uhid }})
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold">Chief Complaints / Symptoms</label>
                                                    <p class="border rounded p-2 bg-light small">{{ $app->notes ?? 'None recorded by reception.' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold">Known Medical History / Allergies</label>
                                                    <p class="border rounded p-2 bg-light small text-danger">{{ $app->patient->medical_history ?? 'None recorded.' }}</p>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Clinical Diagnosis</label>
                                                <input type="text" name="diagnosis" class="form-control" placeholder="e.g. Acute Viral Bronchitis, Stage 1 Hypertension" value="{{ $app->diagnosis }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Prescription (Rx) & Advice</label>
                                                <textarea name="prescription" rows="4" class="form-control font-monospace" placeholder="1. Tab Paracetamol 650mg TDS x 3 days&#10;2. Tab Azithromycin 500mg OD x 5 days&#10;3. Adequate hydration & rest" required>{{ $app->prescription }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Encounter Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="completed" {{ $app->status === 'completed' ? 'selected' : '' }}>Completed (Prescribed)</option>
                                                    <option value="scheduled" {{ $app->status === 'scheduled' ? 'selected' : '' }}>Follow-up / Keep Open</option>
                                                    <option value="cancelled" {{ $app->status === 'cancelled' ? 'selected' : '' }}>Patient Did Not Attend</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-hospital-primary">
                                                <i class="bi bi-save me-1"></i> Save Clinical Record & Complete
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle fs-3 text-success d-block mb-2"></i>
                        No pending patients in your queue today!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Past Consultations History -->
<div class="card card-table">
    <div class="card-header bg-dark text-white p-3 fw-semibold">
        <i class="bi bi-archive-fill me-2 text-primary"></i>My Recent Consultations Archive
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Diagnosis</th>
                    <th>Prescription (Rx)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pastAppointments as $past)
                <tr>
                    <td><small class="fw-semibold">{{ date('d M Y', strtotime($past->appointment_date)) }}</small></td>
                    <td>
                        <div class="fw-bold">{{ $past->patient->name }}</div>
                        <small class="text-muted">{{ $past->patient->uhid }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $past->diagnosis ?? 'General Checkup' }}</span></td>
                    <td><small class="text-muted font-monospace">{{ Str::limit($past->prescription, 50) ?? 'N/A' }}</small></td>
                    <td><span class="badge bg-success">Completed</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-3 text-muted">No past consultation records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
