@extends('backend.layouts.master')

@section('title')
    Edit Candidate
@endsection

@section('admin-content')
    <div class="page-title-area">
        <h4 class="page-title">Edit Candidate</h4>
    </div>

    <div class="main-content-inner">
        <div class="card">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.candidate.update', $candidate->id) }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <label>Applying For *</label>
                        <input type="text" name="applying_for" class="form-control"
                            value="{{ $candidate->applying_for }}" required>
                    </div>

                    <div class="form-group">
                        <label>Referred By</label>
                        <input type="text" name="referred_by" class="form-control" value="{{ $candidate->referred_by }}">
                    </div>

                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ $candidate->name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Father's / Husband's Name *</label>
                        <input type="text" name="father_or_husband_name" class="form-control"
                            value="{{ $candidate->father_or_husband_name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Mobile 1 *</label>
                        <input type="text" name="mobile_1" class="form-control" value="{{ $candidate->mobile_1 }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Mobile 2</label>
                        <input type="text" name="mobile_2" class="form-control" value="{{ $candidate->mobile_2 }}">
                    </div>

                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ $candidate->email }}" required>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="date" name="dob" class="form-control" value="{{ $candidate->dob }}" required>
                    </div>

                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Choose</option>
                            <option value="Male" {{ $candidate->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $candidate->gender == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ $candidate->gender == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Marital Status *</label>
                        <select name="marital_status" class="form-control" required>
                            <option value="">Choose</option>
                            @foreach (['Unmarried', 'Engaged', 'Married', 'Divorce', 'NA'] as $status)
                                <option value="{{ $status }}"
                                    {{ $candidate->marital_status == $status ? 'selected' : '' }}>{{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Education *</label><br>
                        @foreach (['SSC', 'HSC', 'GRADUATION', 'MASTER', 'ART', 'COMMERCE', 'SCIENCE', 'ENGINEERING', 'MBA', 'OTHERS'] as $edu)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="education" value="{{ $edu }}"
                                    {{ $candidate->education == $edu ? 'checked' : '' }} required>
                                <label class="form-check-label">{{ $edu }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label>Job Experience *</label>
                        <select name="job_experience" class="form-control" required>
                            <option value="">Choose</option>
                            @foreach (['One Year', 'Two Years', 'Three Years', 'Four Years', 'Five Years', 'More Than Five Years', 'Fresher'] as $exp)
                                <option value="{{ $exp }}"
                                    {{ $candidate->job_experience == $exp ? 'selected' : '' }}>{{ $exp }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Resident Type *</label>
                        <select name="resident_type" class="form-control" required>
                            <option value="">Choose</option>
                            @foreach (['Family owned', 'Ownership', 'Rented', 'With Relatives', 'P.G.Hostel', 'Not Mentioned'] as $res)
                                <option value="{{ $res }}"
                                    {{ $candidate->resident_type == $res ? 'selected' : '' }}>{{ $res }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Traveling Mode *</label>
                        <select name="traveling_mode" class="form-control" required>
                            <option value="">Choose</option>
                            <option value="Locally" {{ $candidate->traveling_mode == 'Locally' ? 'selected' : '' }}>Locally
                            </option>
                            <option value="Up-down" {{ $candidate->traveling_mode == 'Up-down' ? 'selected' : '' }}>Up-down
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Address 1 *</label>
                        <input type="text" name="address_1" class="form-control" value="{{ $candidate->address_1 }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Address 2 *</label>
                        <input type="text" name="address_2" class="form-control" value="{{ $candidate->address_2 }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Landmark *</label>
                        <input type="text" name="landmark" class="form-control" value="{{ $candidate->landmark }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" name="city" class="form-control" value="{{ $candidate->city }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Pin Code *</label>
                        <input type="text" name="pin_code" class="form-control" value="{{ $candidate->pin_code }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Last Company</label>
                        <input type="text" name="last_company" class="form-control"
                            value="{{ $candidate->last_company }}">
                    </div>

                    <div class="form-group">
                        <label>Last Designation</label>
                        <input type="text" name="last_designation" class="form-control"
                            value="{{ $candidate->last_designation }}">
                    </div>

                    <div class="form-group">
                        <label>Last Salary</label>
                        <input type="text" name="last_salary" class="form-control"
                            value="{{ $candidate->last_salary }}">
                    </div>

                    <div class="form-group">
                        <label>Expected Salary</label>
                        <input type="text" name="expected_salary" class="form-control"
                            value="{{ $candidate->expected_salary }}">
                    </div>

                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ $candidate->remarks }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Candidate</button>
                    <a href="{{ route('admin.candidate.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
