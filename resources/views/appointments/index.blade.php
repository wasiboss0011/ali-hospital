@extends('layouts.app')

@section('title', 'OPD Appointments Schedule')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">OPD Appointments Schedule</h3>
        <p class="text-muted small mb-0">Daily patient bookings, department queues, and doctor allocations</p>
    </div>
    <a href="{{ route('appointments.create') }}" class="btn btn-hospital-red">
        <i class="bi bi-calendar-plus me-1"></i> Book New Appointment
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('appointments.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Filter by Date">
            </div>
            <div class="col-md-3">
                <select name="doctor_id" class="form-select">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
                            {{ $doc->name }} ({{ $doc->department->name ?? 'Dept' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="routine" {{ request('priority') == 'routine' ? 'selected' : '' }}>Routine</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="emergency" {{ request('priority') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary" title="Reset Filters"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Appointments Table -->
<div class="card card-table">
    <div class="card-header card-header-navy d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar3 me-2"></i>Appointments Registry</span>
        <span class="badge bg-light text-dark">{{ $appointments->total() }} Scheduled</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>Token #</th>
                    <th>Date & Time</th>
                    <th>Patient UHID & Name</th>
                    <th>Doctor & Department</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Symptoms / Reason</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $app)
                <tr>
                    <td>
                        <span class="badge bg-dark rounded-pill px-3 py-2 fs-6">#{{ $app->token_number }}</span>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ date('d M Y', strtotime($app->appointment_date)) }}</div>
                        <small class="text-muted">{{ $app->appointment_time ? date('h:i A', strtotime($app->appointment_time)) : 'OPD Slot' }}</small>
                    </td>
                    <td>
                        <a href="{{ route('patients.show', $app->patient->id) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $app->patient->name }}
                        </a>
                        <div><small class="badge bg-secondary font-monospace">{{ $app->patient->uhid }}</small></div>
                    </td>
                    <td>
                        <div class="fw-semibold text-primary">{{ $app->doctor->name }}</div>
                        <small class="text-muted">{{ $app->department->name }} (Fee: ₹{{ $app->doctor->consultation_fee }})</small>
                    </td>
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
                    <td>
                        <small class="text-muted">{{ Str::limit($app->notes ?? 'Routine Consultation', 30) }}</small>
                    </td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Status
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <form method="POST" action="{{ route('appointments.status', $app->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button class="dropdown-item text-success" type="submit">
                                            <i class="bi bi-check-circle me-1"></i> Mark Completed
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('appointments.status', $app->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="bi bi-x-circle me-1"></i> Cancel Appointment
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-calendar2-x fs-1 text-secondary d-block mb-2"></i>
                        No appointments found matching the selected filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($appointments->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
        {{ $appointments->links() }}
    </div>
    @endif
</div>
@endsection
