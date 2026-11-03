@extends('layouts.app')

@section('title', 'Register New Patient')

@section('content')
<div class="mb-4">
    <a href="{{ route('patients.index') }}" class="text-decoration-none text-muted small">
        &larr; Back to Patient Registry
    </a>
    <h3 class="fw-bold mb-1 mt-2 text-dark">Register New Patient</h3>
    <p class="text-muted small">Generates a permanent Unique Health Identification Number (UHID) for Ali's Hospital EMR</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-navy">
                <i class="bi bi-person-badge-fill me-2"></i>Patient Demographic & Clinical Profile
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

                <form method="POST" action="{{ route('patients.store') }}">
                    @csrf

                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">1. Primary Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Mohammad Rizwan" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Age (Years)</label>
                            <input type="number" name="age" class="form-control" placeholder="e.g. 42" value="{{ old('age') }}" min="0" max="130">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Blood Group</label>
                            <select name="blood_group" class="form-select">
                                <option value="">Unknown / Select</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Primary Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. 9876543210" value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">2. Contact & Emergency Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Residential Address</label>
                            <input type="text" name="address" class="form-control" placeholder="e.g. Campbell Road, Ekta Nagar, Lucknow" value="{{ old('address') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Emergency Contact (Name & Phone)</label>
                            <input type="text" name="emergency_contact" class="form-control" placeholder="e.g. Brother: 9876543211" value="{{ old('emergency_contact') }}">
                        </div>
                    </div>

                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">3. Medical History & Chronic Conditions</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Allergies, Known Conditions, Past Surgeries</label>
                            <textarea name="medical_history" rows="3" class="form-control" placeholder="e.g. Penicillin allergy, Type 2 Diabetes diagnosed 2021, Hypertension">{{ old('medical_history') }}</textarea>
                            <small class="text-muted">Recorded into the permanent clinical file for Ali's Hospital doctors.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-hospital-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Register & Issue UHID
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
