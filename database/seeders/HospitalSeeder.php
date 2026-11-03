<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+91-9876543210',
            'is_active' => true,
        ]);

        // 2. Create Departments
        $departments = [
            ['name' => 'Cardiology', 'description' => 'Heart and cardiovascular care', 'location' => '1st Floor'],
            ['name' => 'Neurology', 'description' => 'Brain and nervous system disorders', 'location' => '1st Floor'],
            ['name' => 'Orthopedics', 'description' => 'Bone, joint and muscle care', 'location' => '2nd Floor'],
            ['name' => 'General Medicine', 'description' => 'Primary healthcare and internal medicine', 'location' => 'Ground Floor'],
            ['name' => 'Gynecology', 'description' => 'Women\'s health and childbirth', 'location' => '2nd Floor'],
            ['name' => 'Pediatrics', 'description' => 'Child healthcare', 'location' => 'Ground Floor'],
        ];

        $deptIds = [];
        foreach ($departments as $dept) {
            $deptIds[] = Department::create($dept)->id;
        }

        // 3. Create Doctor User & Profile (General Medicine)
        $doctorUser1 = User::create([
            'name' => 'Dr. Rakesh Gupta',
            'email' => 'doctor@hospital.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
            'phone' => '+91-9876543220',
            'is_active' => true,
        ]);

        Doctor::create([
            'user_id' => $doctorUser1->id,
            'department_id' => $deptIds[3], // General Medicine
            'specialization' => 'General Physician',
            'qualification' => 'MBBS, MD (Medicine)',
            'registration_number' => 'UP-MED-12345',
            'consultation_fee' => 500,
            'room_number' => 'OPD-1',
            'bio' => 'Experienced general physician with 15+ years of practice',
            'is_available' => true,
        ]);

        // 4. Create Staff User
        $staff = User::create([
            'name' => 'Reception Staff',
            'email' => 'staff@hospital.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'phone' => '+91-9876543211',
            'is_active' => true,
        ]);

        // 5. Create Sample Patients
        $patient1 = Patient::create([
            'uhid' => 'AH-2026-0001',
            'name' => 'Ramesh Kumar',
            'age' => 45,
            'gender' => 'male',
            'phone' => '+91-9876501234',
            'blood_group' => 'B+',
            'address' => 'Campbell Road, Mozzam Nagar, Lucknow 226003',
            'emergency_contact_name' => 'Sunita Kumar',
            'emergency_contact_phone' => '+91-9876501235',
        ]);

        $patient2 = Patient::create([
            'uhid' => 'AH-2026-0002',
            'name' => 'Fatima Begum',
            'age' => 32,
            'gender' => 'female',
            'phone' => '+91-9876502345',
            'blood_group' => 'O+',
            'address' => 'Ekta Nagar, Near Campbell Road, Lucknow 226003',
            'emergency_contact_name' => 'Mohammed Ansari',
            'emergency_contact_phone' => '+91-9876502346',
        ]);

        $patient3 = Patient::create([
            'uhid' => 'AH-2026-0003',
            'name' => 'Rajesh Tiwari',
            'age' => 58,
            'gender' => 'male',
            'phone' => '+91-9876503456',
            'blood_group' => 'A+',
            'address' => 'Old Lucknow, Near Ali\'s Hospital',
            'emergency_contact_name' => 'Meera Tiwari',
            'emergency_contact_phone' => '+91-9876503457',
            'medical_history' => 'Diabetes, Hypertension',
        ]);

        // 6. Create Today's Appointments
        Appointment::create([
            'patient_id' => $patient1->id,
            'doctor_id' => 1,
            'department_id' => $deptIds[3],
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '10:00:00',
            'token_number' => 1,
            'status' => 'in_consultation',
            'priority' => 'routine',
            'symptoms' => 'Fever and cold since 2 days',
        ]);

        Appointment::create([
            'patient_id' => $patient2->id,
            'doctor_id' => 1,
            'department_id' => $deptIds[3],
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '10:30:00',
            'token_number' => 2,
            'status' => 'scheduled',
            'priority' => 'urgent',
            'symptoms' => 'Severe headache and dizziness',
        ]);

        Appointment::create([
            'patient_id' => $patient3->id,
            'doctor_id' => 1,
            'department_id' => $deptIds[3],
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '11:00:00',
            'token_number' => 3,
            'status' => 'scheduled',
            'priority' => 'routine',
            'symptoms' => 'Regular diabetes checkup',
        ]);
    }
}
