<?php


// app/Http/Controllers/CandidateRegistrationController.php

namespace App\Http\Controllers;

use App\Models\CandidateRegistration;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CandidateRegistrationExport;

class CandidateRegistrationController extends Controller
{
    public function form()
    {
        return view('backend.pages.candidate_registrations.candidate_form');
    }


    public function create()
    {
        return view('backend.pages.candidate_registrations.candidate_form');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $data = $request->validate([
                'applying_for' => 'required|string|max:255',
                'referred_by' => 'nullable|string|max:255',
                'name' => 'required|string|max:255',
                'father_or_husband_name' => 'required|string|max:255',
                'mobile_1' => 'required|string|max:20',
                'mobile_2' => 'nullable|string|max:20',
                'email' => 'required|email|max:255',
                'dob' => 'required|date',
                'gender' => 'required|string',
                'marital_status' => 'required|string',
                'education' => 'required|string',
                'job_experience' => 'required|string',
                'resident_type' => 'required|string',
                'traveling_mode' => 'required|string',
                'landmark' => 'required|string|max:255',
                'address_1' => 'required|string|max:255',
                'address_2' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'pin_code' => 'required|string|max:10',
                'last_company' => 'nullable|string|max:255',
                'last_designation' => 'nullable|string|max:255',
                'last_salary' => 'nullable|string|max:50',
                'expected_salary' => 'nullable|string|max:50',
                'remarks' => 'nullable|string|max:1000',
            ]);

            CandidateRegistration::create($data);

            return redirect()->route('candidate.form')->with('success', 'Candidate registered successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }


    public function index()
    {
        $candidates = CandidateRegistration::latest()->paginate(20);
        return view('backend.pages.candidate_registrations.index', compact('candidates'));
    }

    public function show($id)
    {
        $candidate = CandidateRegistration::findOrFail($id);
        return view('backend.pages.candidate_registrations.view', compact('candidate'));
    }

    public function edit($id)
    {
        $candidate = CandidateRegistration::findOrFail($id);
        return view('backend.pages.candidate_registrations.edit', compact('candidate'));
    }

    public function update(Request $request, $id)
    {
        $candidate = CandidateRegistration::findOrFail($id);

        $data = $request->validate([
            'applying_for' => 'required|string|max:255',
            'referred_by' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'father_or_husband_name' => 'required|string|max:255',
            'mobile_1' => 'required|string|max:20',
            'mobile_2' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'marital_status' => 'required|string',
            'education' => 'required|string',
            'job_experience' => 'required|string',
            'resident_type' => 'required|string',
            'traveling_mode' => 'required|string',
            'landmark' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pin_code' => 'required|string|max:10',
            'last_company' => 'nullable|string|max:255',
            'last_designation' => 'nullable|string|max:255',
            'last_salary' => 'nullable|string|max:50',
            'expected_salary' => 'nullable|string|max:50',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $candidate->update($data);

        return redirect()->route('admin.candidate.index')->with('success', 'Candidate updated successfully!');
    }

    public function destroy($id)
    {
        $candidate = CandidateRegistration::findOrFail($id);
        $candidate->delete();

        return redirect()->route('admin.candidate.index')->with('success', 'Candidate deleted successfully!');
    }

    public function export(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        return Excel::download(new CandidateRegistrationExport($from, $to), 'candidate_registrations.xlsx');
    }
}