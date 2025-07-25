@extends('backend.layouts.master')

@section('title')
Plan Edit - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection

@section('admin-content')
<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Plan Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.plan.index') }}">All Plans</a></li>
                    <li><span>Edit Plan - {{ $admin->plan_name }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Edit Plan - {{ $admin->plan_name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.plan.update', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Plan Name</label>
                                <input type="text" class="form-control" id="plan_name" name="plan_name"
                                    placeholder="Enter Plan Name" value="{{ $admin->plan_name }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Price (In Ahmedabad)</label>
                                <input type="number" class="form-control" id="price" name="price"
                                    placeholder="Enter Price" value="{{ $admin->price }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Price (Within India)</label>
                                <input type="number" class="form-control" id="price_within_india"
                                    name="price_within_india" placeholder="Enter Price Within India"
                                    value="{{ $admin->price_within_india }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Price (Within Gujrat)</label>
                                <input type="number" class="form-control" id="price_within_gujrat"
                                    name="price_within_gujrat" placeholder="Enter Price Within Gujrat"
                                    value="{{ $admin->price_within_gujrat }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Duration</label>
                                <input type="text" class="form-control" id="validity" name="validity"
                                    placeholder="Enter duration" value="{{ $admin->validity }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Session</label>
                                <input type="text" class="form-control" id="session" name="session"
                                    placeholder="Enter Session" value="{{ $admin->session }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Month Duration</label>
                                <input type="text" class="form-control" id="month_duration" name="month_duration"
                                    placeholder="Enter Month Duration" value="{{ $admin->month_duration }}" required
                                    autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Personal Meeting</label>
                                <input type="text" class="form-control" id="personal_meeting" name="personal_meeting"
                                    placeholder="Enter Personal Meeting" value="{{ $admin->personal_meeting }}" required
                                    autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Deliveries</label>
                                <input type="text" class="form-control" id="deliveries" name="deliveries"
                                    placeholder="Enter MOnth Duration" value="{{ $admin->deliveries }}" required
                                    autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Duration Year</label>
                                <input type="text" class="form-control" id="duration_year" name="duration_year"
                                    placeholder="Enter Personal Meeting" value="{{ $admin->duration_year }}" required
                                    autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Meeting Duration (In Hrs)</label>
                                <input type="number" class="form-control" id="duration" name="duration"
                                    placeholder="Enter Duration" value="{{ $admin->duration }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Tax (In %)</label>
                                <input type="number" class="form-control" id="tax" name="tax" placeholder="Enter Tax"
                                    value="{{ $admin->tax }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Sort Description</label>
                                <textarea required class="form-control" id="sort_desc"
                                    name="sort_desc">{{ $admin->sort_desc }}</textarea>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Description</label>
                                <textarea class="form-control" id="description"
                                    name="description">{{ $admin->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Plan Type</label>
                                <select class="form-control " id="plan_type" name="plan_type" required>
                                    <option value="Personal" {{ old('plan_type')=='Personal' ? 'selected' : '' }} {{
                                        $admin->plan_type == 'Personal' ? 'selected' : '' }}>
                                        Personal
                                    </option>
                                    <option value="Business" {{ old('plan_type')=='Business' ? 'selected' : '' }} {{
                                        $admin->plan_type == 'Business' ? 'selected' : '' }}>
                                        Business
                                    </option>
                                    <option value="Developer" {{ old('plan_type')=='Developer' ? 'selected' : '' }} {{
                                        $admin->plan_type == 'Developer' ? 'selected' : '' }}>
                                        Developer
                                    </option>
                                    <option value="Smart" {{ old('plan_type')=='Smart' ? 'selected' : '' }} {{ $admin->
                                        plan_type == 'Smart' ? 'selected' : '' }}>
                                        Smart
                                    </option>
                                    <option value="Beginner" {{ old('plan_type')=='Beginner' ? 'selected' : '' }} {{
                                        $admin->plan_type == 'Beginner' ? 'selected' : '' }}>
                                        Beginner
                                    </option>

                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">On Call Support</label>
                                <input type="number" class="form-control" id="on_call_support" name="on_call_support"
                                    placeholder="Enter On Call Support" value="{{ $admin->on_call_support }}" required
                                    autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">CMD Visit</label>
                                <input type="number" class="form-control" id="cmd_visit" name="cmd_visit"
                                    placeholder="Enter cmd visit" value="{{ $admin->cmd_visit }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Store Visit</label>
                                <input type="number" class="form-control" id="store_visit" name="store_visit"
                                    placeholder="Enter store visit" value="{{ $admin->store_visit }}" required
                                    autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Select Document</label>
                                <select class="form-control " id="documents" name="documents" required>
                                    <option>Select Document</option>
                                    <option value="Printed Document" {{ old('documents')=='Printed Document'
                                        ? 'selected' : '' }} {{ $admin->
                                        documents == 'Printed Document' ? 'selected' : '' }}>
                                        Printed Document
                                    </option>
                                    <option value="Printed Document + Excel Sheet" {{
                                        old('documents')=='Printed Document + Excel Sheet' ? 'selected' : '' }} {{
                                        $admin->
                                        documents == 'Printed Document + Excel Sheet' ? 'selected' : '' }}>
                                        Printed Document + Excel Sheet
                                    </option>
                                    <option value="Printed Document + Excel Sheet + Training" {{
                                        old('documents')=='Printed Document + Excel Sheet + Training' ? 'selected' : ''
                                        }} {{ $admin->
                                        documents == 'Printed Document + Excel Sheet + Training' ? 'selected' : '' }}>
                                        Printed Document + Excel Sheet + Training
                                    </option>
                                    <option value="Printed Document + Excel Sheet + Training + Solutions" {{
                                        old('documents')=='Printed Document + Excel Sheet + Training + Solutions'
                                        ? 'selected' : '' }} {{ $admin->
                                        documents == 'Printed Document + Excel Sheet + Training + Solutions' ?
                                        'selected' : '' }}>
                                        Printed Document + Excel Sheet + Training + Solutions
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Persionalization</label>
                                <select class="form-control " id="personalization" name="personalization" required>
                                    <option>Select Persionalization</option>
                                    <option value="Face 2 Face" {{ old('personalization')=='Face 2 Face' ? 'selected'
                                        : '' }} {{ $admin->
                                        personalization == 'Face 2 Face'
                                        ? 'selected' : '' }}>
                                        Face 2 Face
                                    </option>
                                    <option value="Online" {{ old('personalization')=='Online' ? 'selected' : '' }} {{
                                        $admin->personalization ==
                                        'Online'
                                        ? 'selected' : '' }}>
                                        Online
                                    </option>
                                </select>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Plan Image</label>
                                <input type="file" name="image" id="image" class="form-control" />
                                <br />
                                <img src="{{ asset('plans/' . $admin->image) }}" alt="Plan Image" width="100px"
                                    height="80px" />
                            </div>
                        </div>
                        {{-- <div class="form-row">
                            <div class="form-group col-md-12 col-sm-12">
                                <label for="cmd_visit">Modules</label>
                                @php
                                $selectedModules = explode(',', $admin->module_ids); // Convert string to array
                                @endphp
                                @foreach ($modules as $module)
                                <div>
                                    <input type="checkbox" id="modules_{{ $module->id }}" name="modules[]"
                                        value="{{ $module->id }}" {{ in_array($module->id, $selectedModules) ? 'checked'
                                    : '' }}>
                                    <label for="modules_{{ $module->id }}"> {{ $module->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div> --}}
                        <div class="form-row">
                            <div class="form-group col-md-12 col-sm-12">
                                <label for="cmd_visit">Services</label>
                                <div class="row">
                                    @php
                                    $chunks = $modules->chunk(ceil($modules->count() / 3)); // Split into 3 sections
                                    $selectedModules = explode(',', $admin->module_ids); // Convert string to array
                                    @endphp

                                    @foreach ($chunks as $chunk)
                                    <div class="col-md-4">
                                        <!-- Adjust width as needed -->
                                        @foreach ($chunk as $module)
                                        <div>
                                            <input type="checkbox" id="modules_{{ $module->id }}" name="modules[]"
                                                value="{{ $module->id }}" {{ in_array($module->id, $selectedModules) ?
                                            'checked' : '' }}>
                                            <label for="modules_{{ $module->id }}">
                                                {{ $module->title }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                                <div id="module-error" style="color: red; display: none; font-weight: bold;"></div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1" {{ old('status')=='1' ? 'selected' : '' }} {{ $admin->status ==
                                        '1' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0" {{ old('status')=='0' ? 'selected' : '' }} {{ $admin->status ==
                                        '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="page_type" id="page_type" value="{{ $page_type }}" />
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.plan.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
            $('.select2').select2();
        })
</script>
<script>
    $(document).ready(function() {
            $('form').on('submit', function(e) {
                const checkedModules = $('input[name="modules[]"]:checked').length;

                if (checkedModules < 2) {
                    e.preventDefault(); // Prevent form submission
                    $('#module-error').text("Please select at least 2 services.").show();
                } else {
                    $('#module-error').hide(); // Clear error if validation passes
                }
            });
        });
</script>
@endsection