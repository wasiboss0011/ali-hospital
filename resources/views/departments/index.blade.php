@extends('layouts.app')

@section('title', 'Clinical Departments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">Clinical Departments</h3>
        <p class="text-muted small mb-0">Specialized medical wings and active consultant allocation at Ali's Hospital</p>
    </div>
    @if(Auth::user()->isAdmin())
    <button type="button" class="btn btn-hospital-primary" data-bs-toggle="modal" data-bs-target="#newDeptModal">
        <i class="bi bi-plus-circle me-1"></i> Add Clinical Department
    </button>
    @endif
</div>

<!-- Department Cards Grid -->
<div class="row g-4 mb-4">
    @forelse($departments as $dept)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid var(--hosp-blue-light) !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-secondary font-monospace">{{ $dept->code ?? 'DEPT' }}</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-person-badge me-1"></i>{{ $dept->doctors_count ?? $dept->doctors->count() }} Doctors
                    </span>
                </div>
                <h5 class="fw-bold text-dark mt-2 mb-1">{{ $dept->name }}</h5>
                <p class="text-muted small mb-3">
                    {{ $dept->description ?? 'Specialized clinical outpatient and inpatient care department.' }}
                </p>

                <div class="border-top pt-3">
                    <small class="fw-bold text-muted d-block mb-2">Active Consultants:</small>
                    <ul class="list-unstyled mb-0 small">
                        @forelse($dept->doctors as $doc)
                            <li class="mb-1 text-dark">
                                <i class="bi bi-person-fill text-danger me-1"></i>
                                Dr. {{ $doc->name }} <span class="text-muted">({{ $doc->specialization }})</span>
                            </li>
                        @empty
                            <li class="text-muted italic">No consultants currently assigned</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="card-footer bg-light border-0 p-3 text-end">
                <a href="{{ route('appointments.create', ['department_id' => $dept->id]) }}" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-calendar-plus me-1"></i> Book OPD
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        No clinical departments registered.
    </div>
    @endforelse
</div>

<!-- Modal: Add Department -->
<div class="modal fade" id="newDeptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('departments.store') }}">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-building-add me-2 text-danger"></i>Add Clinical Department
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Department Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Oncology, Dermatology" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Department Code (Short)</label>
                        <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. ONCO, DERM">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description / Scope of Care</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Brief outline of services provided in this wing..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-hospital-primary">
                        <i class="bi bi-check-circle me-1"></i> Create Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
