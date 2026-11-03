@extends('layouts.app')

@section('title', 'Medical Staff & Consultants Directory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">Medical Staff & Consultants</h3>
        <p class="text-muted small mb-0">Active clinical practitioners and department duty rosters at Ali's Hospital</p>
    </div>
    @if(Auth::user()->isAdmin())
    <a href="{{ route('doctors.create') }}" class="btn btn-hospital-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Onboard New Doctor
    </a>
    @endif
</div>

<!-- Doctors Table -->
<div class="card card-table">
    <div class="card-header card-header-navy d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person-badge-fill me-2"></i>Consultants Directory</span>
        <span class="badge bg-light text-dark">{{ $doctors->count() }} Doctors Listed</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>Doctor Name</th>
                    <th>Clinical Department</th>
                    <th>Specialization & Qualifications</th>
                    <th>OPD Cabin</th>
                    <th>Consultation Fee</th>
                    <th>Contact</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doc)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 me-2">
                                <i class="bi bi-person-fill fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Dr. {{ $doc->name }}</div>
                                <small class="text-muted">{{ $doc->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2">
                            {{ $doc->department->name ?? 'General' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $doc->specialization }}</div>
                        <small class="text-muted">{{ $doc->qualification ?? 'MBBS, MD' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $doc->room_number ?? 'OPD 1' }}</span>
                    </td>
                    <td>
                        <span class="fw-bold text-success">₹{{ number_format($doc->consultation_fee, 2) }}</span>
                    </td>
                    <td>
                        <small><i class="bi bi-telephone text-muted me-1"></i>{{ $doc->phone ?? 'N/A' }}</small>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('appointments.create', ['doctor_id' => $doc->id]) }}" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-calendar-plus me-1"></i> Book Slot
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        No doctors onboarded yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
