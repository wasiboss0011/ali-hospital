<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Patient;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee("Ali's Hospital", false);
    }

    public function test_admin_can_login_and_view_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Hospital Admin', false);
    }

    public function test_doctor_can_view_consultation_queue(): void
    {
        $doctorUser = User::where('role', 'doctor')->first();
        $this->assertNotNull($doctorUser);

        $response = $this->actingAs($doctorUser)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Doctor Consultation Console', false);
    }

    public function test_patient_registration_assigns_uhid_format(): void
    {
        $staff = User::where('role', 'staff')->first();
        $this->assertNotNull($staff);

        $response = $this->actingAs($staff)->post('/patients', [
            'name' => 'Mohd Test Patient',
            'gender' => 'male',
            'phone' => '9876543210',
            'age' => 40,
            'date_of_birth' => '1986-05-12',
            'blood_group' => 'B+',
            'address' => 'Campbell Road, Lucknow',
        ]);

        $response->assertRedirect(route('patients.index'));
        $this->assertDatabaseHas('patients', [
            'name' => 'Mohd Test Patient',
            'phone' => '9876543210',
        ]);
    }

    public function test_staff_cannot_create_doctor_account(): void
    {
        $staff = User::where('role', 'staff')->first();
        $dept = Department::first();

        $response = $this->actingAs($staff)->post('/doctors', [
            'name' => 'Unauthorized Doctor',
            'email' => 'fake@hospital.com',
            'password' => 'secret1234',
            'department_id' => $dept->id,
            'specialization' => 'General',
            'consultation_fee' => 500,
        ]);

        $response->assertStatus(403);
    }
}
