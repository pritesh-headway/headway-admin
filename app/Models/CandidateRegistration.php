<?php



// app/Models/CandidateRegistration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'applying_for',
        'referred_by',
        'name',
        'father_or_husband_name',
        'mobile_1',
        'mobile_2',
        'email',
        'dob',
        'gender',
        'marital_status',
        'education',
        'job_experience',
        'resident_type',
        'traveling_mode',
        'address_1',
        'address_2',
        'landmark',
        'city',
        'pin_code',
        'last_company',
        'last_designation',
        'last_salary',
        'expected_salary',
        'remarks'
    ];
}
