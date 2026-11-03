<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'uhid',
        'name',
        'age',
        'date_of_birth',
        'gender',
        'phone',
        'blood_group',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_history',
        'allergies',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getEmergencyContactAttribute()
    {
        if ($this->emergency_contact_name && $this->emergency_contact_phone) {
            return $this->emergency_contact_name . ' (' . $this->emergency_contact_phone . ')';
        }
        return $this->emergency_contact_name ?? $this->emergency_contact_phone ?? null;
    }
}
