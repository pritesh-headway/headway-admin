@extends('backend.layouts.master')

@section('title')
    Edit Visitor
@endsection

@section('admin-content')
    <div class="page-title-area">
        <h4 class="page-title">Edit Exhibition Visitor</h4>
    </div>

    <div class="main-content-inner">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('admin.exibition_visitors.index') }}" class="btn btn-secondary btn-sm mb-3">← Back to
                    List</a>

                <form action="{{ route('admin.exibition_visitors.update', $visitor->id) }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Event Venue</label>
                            <input type="text" name="event_venue" class="form-control"
                                value="{{ old('event_venue', $visitor->event_venue) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Jeweller's Name <span class="text-danger">*</span></label>
                            <input type="text" name="jeweller_name" class="form-control" required
                                value="{{ old('jeweller_name', $visitor->jeweller_name) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Owner's Name <span class="text-danger">*</span></label>
                            <input type="text" name="owner_name" class="form-control" required
                                value="{{ old('owner_name', $visitor->owner_name) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $visitor->email) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Mobile Number (1) <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_1" class="form-control" required
                                value="{{ old('mobile_1', $visitor->mobile_1) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Mobile Number (2)</label>
                            <input type="text" name="mobile_2" class="form-control"
                                value="{{ old('mobile_2', $visitor->mobile_2) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2" required>{{ old('address', $visitor->address) }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" required
                                value="{{ old('city', $visitor->city) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Enquired For <span class="text-danger">*</span></label>
                            <select name="enquired_for" class="form-control" required>
                                <option value="">Select Option</option>
                                @foreach (['Headway Service', 'IT Service', 'SSU Memberships', 'Others'] as $option)
                                    <option value="{{ $option }}"
                                        {{ old('enquired_for', $visitor->enquired_for) == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $visitor->remarks) }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Visitor</button>
                </form>
            </div>
        </div>
    </div>
@endsection
