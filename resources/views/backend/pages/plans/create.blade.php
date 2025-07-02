@extends('backend.layouts.master')

@section('title')
Plan Create - Admin Panel
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
                <h4 class="page-title pull-left">Plan Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.plan.index') }}">All Plans</a></li>
                    <li><span>Create Plan</span></li>
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
                    <h4 class="header-title">Create New Plan</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.plan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Plan Name</label>
                                <input type="text" class="form-control" id="plan_name" name="plan_name"
                                    placeholder="Enter Name" required autofocus value="{{ old('plan_name') }}" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Price (In Ahmedabad)</label>
                                <input type="number" class="form-control" id="price" name="price"
                                    placeholder="Enter Price For Ahmedabad" value="{{ old('price') }}" required
                                    autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Price (Out Of Ahmedabad)</label>
                                <input type="number" class="form-control" id="price_out_of_ah" name="price_out_of_ah"
                                    placeholder="Enter Price Out Of Ahmedabad" value="{{ old('price_out_of_ah') }}"
                                    required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Price (Out Of State)</label>
                                <input type="number" class="form-control" id="price_out_of_st" name="price_out_of_st"
                                    placeholder="Enter Price Out Of State" value="{{ old('price_out_of_st') }}" required
                                    autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Duration</label>
                                <input type="text" class="form-control" id="validity" name="validity"
                                    placeholder="Enter duration" value="{{ old('validity') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Session</label>
                                <input type="text" class="form-control" id="session" name="session"
                                    placeholder="Enter Session" value="{{ old('session') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Month Duration</label>
                                <input type="number" class="form-control" id="month_duration" name="month_duration"
                                    placeholder="Enter Month Duration" value="{{ old('month_duration') }}" required
                                    autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Personal Meeting</label>
                                <input type="text" class="form-control" id="personal_meeting" name="personal_meeting"
                                    placeholder="Enter Personal Meeting" value="{{ old('personal_meeting') }}" required
                                    autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Deliveries</label>
                                <input type="text" class="form-control" id="deliveries" name="deliveries"
                                    placeholder="Enter Deliveries" value="{{ old('deliveries') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Duration Year</label>
                                <input type="text" class="form-control" id="duration_year" name="duration_year"
                                    placeholder="Enter Duration Year" value="{{ old('duration_year') }}" required
                                    autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Meeting Duration (In Hrs)</label>
                                <input type="number" class="form-control" id="duration" name="duration"
                                    placeholder="Enter Meeting Duration" value="{{ old('duration') }}" required
                                    autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Tax (In %)</label>
                                <input type="number" class="form-control" id="tax" name="tax" placeholder="Enter Tax"
                                    value="{{ old('tax') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Sort Description</label>
                                <textarea required class="form-control" id="sort_desc" name="sort_desc"></textarea>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Description</label>
                                <textarea required class="form-control" id="description" name="description"></textarea>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Plan Type</label>
                                <select class="form-control " id="plan_type" name="plan_type" required>
                                    <option value="Member Plan" {{ old('plan_type')=='Member Plan' ? 'selected' : '' }}>
                                        Member Plan
                                    </option>
                                    <option value="Service Plan" {{ old('plan_type')=='Service Plan' ? 'selected' : ''
                                        }}>Service Plan
                                    </option>

                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Plan Image</label>
                                <input type="file" name="image" id="image" class="form-control" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">CMD Visit</label>
                                <input type="number" class="form-control" id="cmd_visit" name="cmd_visit"
                                    placeholder="Enter cmd visit" value="{{ old('cmd_visit') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Store Visit</label>
                                <input type="number" class="form-control" id="store_visit" name="store_visit"
                                    placeholder="Enter store visit" value="{{ old('store_visit') }}" required autofocus>
                            </div>
                        </div>
                        {{-- <div class="form-row">
                            <div class="form-group col-md-12 col-sm-12">
                                <label for="cmd_visit">Modules</label>
                                @foreach ($modules as $module)
                                <div>
                                    <input type="checkbox" id="modules_{{ $module->id }}" name="modules[]"
                                        value="{{ $module->id }}">
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
                                    @endphp

                                    @foreach ($chunks as $chunk)
                                    <div class="col-md-4">
                                        <!-- Adjust width as needed -->
                                        @foreach ($chunk as $module)
                                        <div>
                                            <input type="checkbox" id="modules_{{ $module->id }}" name="modules[]"
                                                value="{{ $module->id }}">
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
                                <select class="form-control " id="status" name="status" required>
                                    <option value="1" {{ old('status')=='1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status')=='0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

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

                if (checkedModules < 4) {
                    e.preventDefault(); // Prevent form submission
                    $('#module-error').text("Please select at least 4 services.").show();
                } else {
                    $('#module-error').hide(); // Clear error if validation passes
                }
            });
        });
</script>
@endsection