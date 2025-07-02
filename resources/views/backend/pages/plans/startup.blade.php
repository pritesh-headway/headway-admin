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
                                <label for="name">Price (Within Gujrat)</label>
                                <input type="number" class="form-control" id="price_within_gujrat"
                                    name="price_within_gujrat" placeholder="Enter Price Within Gujrat"
                                    value="{{ old('price_within_gujrat') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Price (Within India)</label>
                                <input type="number" class="form-control" id="price_within_india"
                                    name="price_within_india" placeholder="Enter Price Within India"
                                    value="{{ old('price_within_india') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Sqrt</label>
                                <input type="text" class="form-control" id="sqrt" name="sqrt" placeholder="Enter Sqrt"
                                    value="{{ old('sqrt') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Employees</label>
                                <input type="text" class="form-control" id="employees" name="employees"
                                    placeholder="Enter Employee" value="{{ old('employees') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Stock</label>
                                <input type="text" class="form-control" id="stock" name="stock"
                                    placeholder="Enter Stock" value="{{ old('stock') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Plan Type</label>
                                <select class="form-control " id="plan_type" name="plan_type" required>
                                    <option value="Small Scale" {{ old('plan_type')=='Small Scale' ? 'selected' : '' }}>
                                        Small Scale
                                    </option>
                                    <option value="Medium Scale" {{ old('plan_type')=='Medium Scale' ? 'selected' : ''
                                        }}>Medium Scale
                                    </option>
                                    <option value="Large Scale" {{ old('plan_type')=='Large Scale' ? 'selected' : '' }}>
                                        Large Scale
                                    </option>
                                </select>
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
                                <label for="password">Plan Image</label>
                                <input type="file" name="image" id="image" class="form-control" required />
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