@extends('layouts.app')

@section('title', 'Patient Registry & Directory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">Patient Registry (EMR / UHID)</h3>
        <p class="text-muted small mb-0">Master clinical index of all registered patients at Ali's Hospital</p>
    </div>
    <a href="{{ route('patients.create') }}" class="btn btn-hospital-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Register New Patient (New UHID)
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('patients.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by UHID, patient name, or phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="gender" class="form-select">
                    <option value="">All Genders</option>
                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="blood_group" class="form-select">
                    <option value="">All Blood Groups</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary" title="Reset Filters"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Patients Data Table -->
<div class="card card-table">
    <div class="card-header card-header-navy d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people-fill me-2"></i>Registered Patients Directory</span>
        <span class="badge bg-light text-dark">{{ $patients->total() }} Records</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>UHID</th>
                    <th>Patient Name</th>
                    <th>Demographics</th>
                    <th>Blood Group</th>
                    <th>Contact Info</th>
                    <th>Emergency Contact</th>
                    <th>Registered</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td>
                        <span class="badge bg-dark font-monospace py-2 px-2">{{ $patient->uhid }}</span>
                    </td>
                    <td>
                        <a href="{{ route('patients.show', $patient->id) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $patient->name }}
                        </a>
                    </td>
                    <td>
                        <span class="text-muted small">{{ $patient->age ? $patient->age . ' yrs' : 'N/A' }} • {{ ucfirst($patient->gender) }}</span>
                    </td>
                    <td>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2">
                            {{ $patient->blood_group ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <div><i class="bi bi-telephone text-muted me-1"></i>{{ $patient->phone }}</div>
                    </td>
                    <td>
                        <small class="text-muted">{{ $patient->emergency_contact ?? 'None listed' }}</small>
                    </td>
                    <td>
                        <small class="text-muted">{{ $patient->created_at->format('d M Y') }}</small>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-outline-primary" title="View Patient File">
                                <i class="bi bi-folder2-open"></i> EMR
                            </a>
                            <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-outline-danger" title="Book Appointment">
                                <i class="bi bi-calendar-plus"></i> Book
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-person-x fs-1 text-secondary d-block mb-2"></i>
                        No patient records found matching your query.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
        {{ $patients->links() }}
    </div>
    @endif
</div>
@endsection
