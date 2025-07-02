@extends('backend.layouts.master')

@section('title')
    View Candidate Details
@endsection

@section('admin-content')
    <div class="page-title-area">
        <h4 class="page-title">Candidate Details</h4>
    </div>

    <div class="main-content-inner">
        <div class="card">
            <div class="card-body">

                <a href="{{ route('admin.candidate.index') }}" class="btn btn-secondary mb-3">← Back to List</a>

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Applying For</th>
                            <td>{{ $candidate->applying_for }}</td>
                        </tr>
                        <tr>
                            <th>Referred By</th>
                            <td>{{ $candidate->refered_by }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $candidate->name }}</td>
                        </tr>
                        <tr>
                            <th>Father's / Husband's Name</th>
                            <td>{{ $candidate->fathers_or_husbands_name }}</td>
                        </tr>
                        <tr>
                            <th>Mobile 1</th>
                            <td>{{ $candidate->mobile_1 }}</td>
                        </tr>
                        <tr>
                            <th>Mobile 2</th>
                            <td>{{ $candidate->mobile_2 }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $candidate->email }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $candidate->dob }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $candidate->gender }}</td>
                        </tr>
                        <tr>
                            <th>Marital Status</th>
                            <td>{{ $candidate->marital_status }}</td>
                        </tr>
                        <tr>
                            <th>Education</th>
                            <td>{{ $candidate->education }}</td>
                        </tr>
                        <tr>
                            <th>Job Experience</th>
                            <td>{{ $candidate->job_experience }}</td>
                        </tr>
                        <tr>
                            <th>Resident Type</th>
                            <td>{{ $candidate->resident_type }}</td>
                        </tr>
                        <tr>
                            <th>Traveling Mode</th>
                            <td>{{ $candidate->traveling_mode }}</td>
                        </tr>
                        <tr>
                            <th>Address 1</th>
                            <td>{{ $candidate->address_1 }}</td>
                        </tr>
                        <tr>
                            <th>Address 2</th>
                            <td>{{ $candidate->address_2 }}</td>
                        </tr>
                        <tr>
                            <th>Landmark</th>
                            <td>{{ $candidate->landmark }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $candidate->city }}</td>
                        </tr>
                        <tr>
                            <th>Pin Code</th>
                            <td>{{ $candidate->pin_code }}</td>
                        </tr>
                        <tr>
                            <th>Last Job Company</th>
                            <td>{{ $candidate->last_job_company }}</td>
                        </tr>
                        <tr>
                            <th>Last Job Designation</th>
                            <td>{{ $candidate->last_job_designation }}</td>
                        </tr>
                        <tr>
                            <th>Last Job Salary</th>
                            <td>{{ $candidate->last_job_salary }}</td>
                        </tr>
                        <tr>
                            <th>Expected Salary</th>
                            <td>{{ $candidate->expected_salary }}</td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td>{{ $candidate->remarks }}</td>
                        </tr>
                        <tr>
                            <th>Submitted At</th>
                            <td>{{ $candidate->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-3">
                    <a href="{{ route('admin.candidate.edit', $candidate->id) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('admin.candidate.delete', $candidate->id) }}" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this candidate?')">Delete</a>
                </div>

            </div>
        </div>
    </div>
@endsection
