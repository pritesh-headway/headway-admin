@extends('backend.layouts.master')

@section('title')
Order Add On Service Edit - Admin Panel
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
                <h4 class="page-title pull-left">Order Add On Service View</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.orderaddon.index') }}">All Order Add On Service</a></li>
                    <li><span>View Order Add On Service - {{ $admin->name }}</span></li>
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
                    <h4 class="header-title">View Order Add On Service - {{ $admin->name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.orderaddon.update', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name"
                                    placeholder="Enter Full Name" value="{{ $admin->name }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Add On Name</label>
                                <input type="text" class="form-control" id="reference_by" name="reference_by"
                                    placeholder="Enter Reference By" value="{{ $admin->title }}" readonly autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Plan Name</label>
                                <input type="text" class="form-control" id="plan_name" name="plan_name"
                                    placeholder="Enter Gender" value="{{ $admin->plan_name }}" readonly autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Payment Receipt</label>
                                {{-- <input type="file" name="payment_receipt" id="payment_receipt"
                                    class="form-control" /> --}}
                                <br />
                                <a href="{{ asset('addon_receipts/'.$admin->payment_receipt) }}" target="_blank"><img
                                        src="{{ asset('addon_receipts/'.$admin->payment_receipt) }}" alt="Blog Image"
                                        width="150px" height="100px" /></a>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Status</label>
                                <select class="form-control " id="status" name="status">
                                    <option value="1" {{ old('status')=='1' ? 'selected' : '' }} {{ $admin->status=='1'
                                        ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status')=='0' ? 'selected' : '' }} {{ $admin->status=='0'
                                        ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Add On Service Status</label>
                                <select class="form-control " id="purchase_status" name="purchase_status">
                                    <option style="background-color: red;" value="Pending" {{
                                        old('purchase_status')=='Pending' ? 'selected' : '' }} {{ $admin->
                                        purchase_status=='Pending'
                                        ? 'selected' : '' }}>Pending
                                    </option>
                                    <option style="background-color: orange;" value="Declined" {{
                                        old('purchase_status')=='Declined' ? 'selected' : '' }} {{ $admin->
                                        purchase_status=='Declined'
                                        ? 'selected' : '' }}>Declined
                                    </option>
                                    <option style="background-color: green;" value="Approved" {{
                                        old('purchase_status')=='Approved' ? 'selected' : '' }} {{ $admin->
                                        purchase_status=='Approved'
                                        ? 'selected' : '' }}>Approved
                                    </option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.membership.index') }}"
                            class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        <style>
            #membership_status {
                padding: 5px;
                font-size: 16px;
            }
        </style>
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
    document.addEventListener("DOMContentLoaded", function() {
        var select = document.getElementById("purchase_status");

        function updateBackgroundColor() {
            var selectedValue = select.value;
            if (selectedValue === "Pending") {
                select.style.backgroundColor = "red";
                select.style.color = "white";
            } else if (selectedValue === "Declined") {
                select.style.backgroundColor = "orange";
                select.style.color = "white";
            } else if (selectedValue === "Approved") {
                select.style.backgroundColor = "green";
                select.style.color = "white";
            } else {
                select.style.backgroundColor = ""; // Default
                select.style.color = "black";
            }
        }

        // Run function on change
        select.addEventListener("change", updateBackgroundColor);

        // Set initial color based on selected value
        updateBackgroundColor();
    });
</script>
@endsection
