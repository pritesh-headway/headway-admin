@extends('backend.layouts.master')

@section('title')
Customer Edit - Admin Panel
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
                <h4 class="page-title pull-left">Customer View</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.customers.index') }}">All Customers</a></li>
                    <li><span>View Customer - {{ $admin->name }}</span></li>
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
                    <h4 class="header-title">View Customer - {{ $admin->name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.customers.update', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Customer Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Customer" value="{{ $admin->name }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Customer Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter Email" value="{{ $admin->email }}" readonly autofocus>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Customer Phone</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="Enter Phone" value="{{ $admin->phone_number }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Customer Alternate Phone</label>
                                <input type="text" class="form-control" id="alternate_phone" name="alternate_phone"
                                    placeholder="Enter Phone" value="{{ $admin->alternate_phone }}" readonly autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Flat No</label>
                                <input type="text" class="form-control" id="flat_no" name="flat_no"
                                    placeholder="Enter flat_no" value="{{ $admin->flat_no }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Area</label>
                                <input type="text" class="form-control" id="area" name="area" placeholder="Enter area"
                                    value="{{ $admin->area }}" readonly autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Landmark</label>
                                <input type="text" class="form-control" id="landmark" name="landmark"
                                    placeholder="Enter Phone" value="{{ $admin->landmark }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">State</label>
                                <input type="text" class="form-control" id="state" name="state"
                                    placeholder="Enter state" value="{{ $admin->state }}" readonly autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Customer City</label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="Enter City"
                                    value="{{ $admin->city }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Pincode</label>
                                <input type="text" class="form-control" id="pincode" name="pincode"
                                    placeholder="Enter Phone" value="{{ $admin->pincode }}" readonly autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Customer Image</label>
                                {{-- <input type="file" name="avatar" id="avatar" class="form-control" /> --}}
                                <br />
                                <img src="{{ asset('profile_images/'.$admin->avatar) }}" alt="Customer Image"
                                    width="100px" height="80px" />
                            </div>

                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Is Verify</label>
                                <select class="form-control " id="is_verify" name="is_verify">
                                    <option value="1" {{ old('is_verify')=='1' ? 'selected' : '' }} {{ $admin->
                                        is_verify=='1'
                                        ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="0" {{ old('is_verify')=='0' ? 'selected' : '' }} {{ $admin->
                                        is_verify=='0'
                                        ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Update Status</button>
                        <a href="{{ route('admin.customers.index') }}"
                            class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
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
@endsection