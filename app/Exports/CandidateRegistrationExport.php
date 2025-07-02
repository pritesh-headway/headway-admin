<?php

namespace App\Exports;

use App\Models\CandidateRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;

class CandidateRegistrationExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $query = CandidateRegistration::query();

        if ($this->from && $this->to) {
            $query->whereBetween('created_at', [$this->from, $this->to]);
        }

        return $query->get([
            'id',
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
            'remarks',
            'created_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Applying For',
            'Referred By',
            'Name',
            'Father/Husband Name',
            'Mobile 1',
            'Mobile 2',
            'Email',
            'DOB',
            'Gender',
            'Marital Status',
            'Education',
            'Job Experience',
            'Resident Type',
            'Traveling Mode',
            'Address 1',
            'Address 2',
            'Landmark',
            'City',
            'Pin Code',
            'Last Company',
            'Last Designation',
            'Last Salary',
            'Expected Salary',
            'Remarks',
            'Created At'
        ];
    }
}