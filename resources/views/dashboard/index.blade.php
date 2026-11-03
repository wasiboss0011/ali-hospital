@extends('layouts.app')

@section('title', 'Hospital Admin & Operations Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">Clinical Operations & OPD Overview</h3>
        <p class="text-muted small mb-0">Live operational telemetry for Ali's Hospital, Lucknow</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('patients.create') }}" class="btn btn-hospital-primary">
            <i class="bi bi-person-plus-fill me-1"></i> New Patient UHID
        </a>
        <a href="{{ route('appointments.create') }}" class="btn btn-hospital-red">
            <i class="bi bi-calendar-plus me-1"></i> Book OPD Consultation
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Total Patients</span>
                    <h3 class="fw-bold my-1 text-dark">{{ $stats['total_patients'] }}</h3>
                    <small class="text-success"><i class="bi bi-arrow-up-right me-1"></i>Registered UHIDs</small>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-stat p-3 red-accent">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Today's Appointments</span>
                    <h3 class="fw-bold my-1 text-dark">{{ $stats['today_appointments'] }}</h3>
                    <small class="text-danger"><i class="bi bi-clock-history me-1"></i>Scheduled OPD</small>
                </div>
                <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger">
                    <i class="bi bi-calendar2-check-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Doctors on Duty</span>
                    <h3 class="fw-bold my-1 text-dark">{{ $stats['total_doctors'] }}</h3>
                    <small class="text-primary"><i class="bi bi-building me-1"></i>Active Consultants</small>
                </div>
                <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                    <i class="bi bi-person-badge-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-stat p-3 red-accent">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Waiting In Queue</span>
                    <h3 class="fw-bold my-1 text-dark">{{ $stats['pending_appointments'] }}</h3>
                    <small class="text-warning"><i class="bi bi-hourglass-split me-1"></i>Pending Token Call</small>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                    <i class="bi bi-stopwatch-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Section: Today's Queue & Recent Patients -->
<div class="row g-4">
    <!-- Live OPD Queue -->
    <div class="col-lg-8">
        <div class="card card-table">
            <div class="card-header card-header-navy d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-task me-2"></i>Today's Live OPD Queue</span>
                <span class="badge bg-light text-dark">{{ $todayAppointments->count() }} Encounters Today</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Token</th>
                            <th>Patient / UHID</th>
                            <th>Doctor / Dept</th>
                            <th>Time</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
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
                            <td>
                                <div class="fw-semibold text-primary">{{ $app->doctor->name }}</div>
                                <small class="text-muted">{{ $app->department->name }}</small>
                            </td>
                            <td><small class="fw-semibold">{{ $app->appointment_time ? date('h:i A', strtotime($app->appointment_time)) : 'Routine OPD' }}</small></td>
                            <td>
                                @if($app->priority === 'emergency')
                                    <span class="badge bg-danger text-uppercase">Emergency</span>
                                @elseif($app->priority === 'urgent')
                                    <span class="badge bg-warning text-dark text-uppercase">Urgent</span>
                                @else
                                    <span class="badge bg-secondary text-uppercase">Routine</span>
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
                            <td class="text-end">
                                @if($app->status === 'scheduled')
                                <form method="POST" action="{{ route('appointments.status', $app->id) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Mark Attended">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-muted small"><i class="bi bi-check2-all text-success"></i> Closed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                                No OPD appointments scheduled for today yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 bg-light border-top text-end">
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">
                    View Full Appointments Archive &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Patient Registrations -->
    <div class="col-lg-4">
        <div class="card card-table">
            <div class="card-header bg-dark text-white p-3 fw-semibold">
                <i class="bi bi-person-lines-fill me-2 text-danger"></i>Recent Patients Registered
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentPatients as $pat)
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <a href="{{ route('patients.show', $pat->id) }}" class="fw-bold text-decoration-none text-dark d-block">
                            {{ $pat->name }}
                        </a>
                        <small class="text-muted">{{ $pat->uhid }} • {{ ucfirst($pat->gender) }}</small>
                    </div>
                    <a href="{{ route('patients.show', $pat->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
                @empty
                <div class="p-3 text-center text-muted">No patients registered.</div>
                @endforelse
            </div>
            <div class="p-3 bg-light border-top text-center">
                <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    Open Patient Directory &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
