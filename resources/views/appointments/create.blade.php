@extends('layouts.app')

@section('title', 'Book OPD Appointment')

@section('content')
<div class="mb-4">
    <a href="{{ route('appointments.index') }}" class="text-decoration-none text-muted small">
        &larr; Back to Appointments Schedule
    </a>
    <h3 class="fw-bold mb-1 mt-2 text-dark">Book OPD Appointment & Token</h3>
    <p class="text-muted small">Allocates next sequential token number for the selected doctor & clinical department</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-navy">
                <i class="bi bi-calendar-check-fill me-2"></i>Consultation Booking Details
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

                <form method="POST" action="{{ route('appointments.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Select Registered Patient (UHID) <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">-- Choose Patient by Name or UHID --</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" {{ (old('patient_id') == $p->id || request('patient_id') == $p->id) ? 'selected' : '' }}>
                                    [{{ $p->uhid }}] {{ $p->name }} — Phone: {{ $p->phone }} ({{ ucfirst($p->gender) }})
                                </option>
                            @endforeach
                        </select>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Not registered yet?</small>
                            <a href="{{ route('patients.create') }}" class="small text-primary fw-semibold text-decoration-none">
                                + Register New Patient First
                            </a>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Department <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select" required onchange="filterDoctors()">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Consulting Doctor <span class="text-danger">*</span></label>
                            <select name="doctor_id" id="doctor_id" class="form-select" required>
                                <option value="">-- Select Doctor --</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" data-dept="{{ $doc->department_id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                                        Dr. {{ $doc->name }} ({{ $doc->specialization }} — Fee: ₹{{ $doc->consultation_fee }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Appointment Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Estimated Time Slot</label>
                            <input type="time" name="appointment_time" class="form-control" value="{{ old('appointment_time', '10:00') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Priority Triage <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select" required>
                                <option value="routine" {{ old('priority') == 'routine' ? 'selected' : '' }}>Routine OPD</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                <option value="emergency" {{ old('priority') == 'emergency' ? 'selected' : '' }}>Emergency / Casualty</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Reason for Visit / Primary Symptoms</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="e.g. Severe chest congestion, persistent high fever since 3 days, headaches">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-hospital-red px-4">
                            <i class="bi bi-calendar-check me-1"></i> Confirm Booking & Issue Token
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function filterDoctors() {
    const deptId = document.getElementById('department_id').value;
    const doctorSelect = document.getElementById('doctor_id');
    const options = doctorSelect.querySelectorAll('option');

    options.forEach(opt => {
        if (!opt.value) return;
        if (!deptId || opt.getAttribute('data-dept') === deptId) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    });
}
</script>
@endsection
