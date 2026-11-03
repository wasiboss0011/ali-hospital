@extends('layouts.app')

@section('title', 'Onboard New Doctor')

@section('content')
<div class="mb-4">
    <a href="{{ route('doctors.index') }}" class="text-decoration-none text-muted small">
        &larr; Back to Doctors Directory
    </a>
    <h3 class="fw-bold mb-1 mt-2 text-dark">Onboard New Consultant Doctor</h3>
    <p class="text-muted small">Creates a doctor profile and linked login credentials for clinical consultations</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-navy">
                <i class="bi bi-person-plus-fill me-2"></i>Doctor Profile & Roster Registration
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('doctors.store') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Doctor Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Dr.</span>
                                <input type="text" name="name" class="form-control" placeholder="Tariq Mansoor" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Department Assignment <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-select" required>
                                <option value="">-- Choose Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Login Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="doctor.name@hospital.com" value="{{ old('email') }}" required>
                            <small class="text-muted">Used by doctor to sign in to clinical portal.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Initial Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" value="password123" required>
                            <small class="text-muted">Default is <code>password123</code></small>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Specialization <span class="text-danger">*</span></label>
                            <input type="text" name="specialization" class="form-control" placeholder="e.g. Interventional Cardiology" value="{{ old('specialization') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Qualifications / Degrees</label>
                            <input type="text" name="qualification" class="form-control" placeholder="e.g. MBBS, MD, DM (Cardiology)" value="{{ old('qualification') }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Consultation Fee (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="consultation_fee" class="form-control" placeholder="500.00" value="{{ old('consultation_fee', '500.00') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">OPD Room / Cabin</label>
                            <input type="text" name="room_number" class="form-control" placeholder="e.g. Room 204, 2nd Floor" value="{{ old('room_number') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. 9876543212" value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-hospital-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Onboard & Create Doctor Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
