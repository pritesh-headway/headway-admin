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
                                    value="{{ $admin->price_within_india }}" autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Price (Within Gujrat)</label>
                                <input type="number" class="form-control" id="price_within_gujrat"
                                    name="price_within_gujrat" placeholder="Enter Price Within Gujrat"
                                    value="{{ $admin->price_within_gujrat }}" autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Meetings</label>
                                <input type="text" class="form-control" id="personal_meeting" name="personal_meeting"
                                    placeholder="Enter Meeting" value="{{ $admin->personal_meeting }}" required
                                    autofocus>
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
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">On Call Support</label>
                                <input type="number" class="form-control" id="on_call_support" name="on_call_support"
                                    placeholder="Enter On Call Support" value="{{ $admin->on_call_support }}" required
                                    autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Marketing Campaign Plan Strategy</label>
                                <input type="text" class="form-control" id="marketing_campaign_plan_startegy"
                                    name="marketing_campaign_plan_startegy"
                                    placeholder="Enter Marketing Campaign Support"
                                    value="{{ $admin->marketing_campaign_plan_startegy }}" required autofocus>
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

                if (checkedModules < 3) {
                    e.preventDefault(); // Prevent form submission
                    $('#module-error').text("Please select at least 3 services.").show();
                } else {
                    $('#module-error').hide(); // Clear error if validation passes
                }
            });
        });
</script>
@endsection