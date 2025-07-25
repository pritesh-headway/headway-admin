@extends('backend.layouts.master')

@section('title')
Stay Aware Alive Batch Request - Admin Panel
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
                <h4 class="page-title pull-left">Stay Aware Alive Batch View</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.stayawarebatch.index') }}">All Stay Aware Alive Batch</a></li>
                    <li><span>View Stay Aware Alive Batch - {{ $admin->owner_name }}</span></li>
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
                    <b>Shop Name:</b>
                    <h4 class="header-title">{{ $admin->shop_name }}</h4>
                    {{-- <b>Batch Assigned:</b> --}}
                    {{-- <h4 class="header-title">{{ $batchDetails->batch ?? 'Pending' }}</h4> --}}
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.stayawarebatch.update', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <input type="hidden" name="admin_id" id="admin_id" value="{{ $admin->id }}">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Owner Name</label>
                                <input type="text" class="form-control" id="owner_name" name="owner_name"
                                    placeholder="Enter Full Name" value="{{ $admin->owner_name }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="Enter Reference By" value="{{ $admin->phone_number }}" readonly
                                    autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject"
                                    placeholder="Enter Gender" value="{{ $admin->subject }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Message</label>
                                <input type="text" class="form-control" id="message" name="message"
                                    placeholder="Enter Gender" value="{{ $admin->message }}" readonly autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Cash Payment Request</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    placeholder="Enter Gender"
                                    value="{{ ($admin->cash_payment_request == 1) ? 'Cash Payment' : 'Online' }}"
                                    readonly autofocus>
                            </div>

                        </div>

                        @if ($admin->cash_payment_request == 1)
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Payment Receipt</label>
                                <br />
                                <a href="{{ asset('revision_batch_receipts/' . $admin->image) }}" target="_blank"><img
                                        src="{{ asset('revision_batch_receipts/' . $admin->image) }}"
                                        alt="revision  batch receipts Image" width="150px" height="100px" /></a>
                            </div>
                        </div>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Revision Batch Status</label>
                                <select class="form-control " id="revison_batch_status" name="revison_batch_status">
                                    <option style="background-color: red;" value="Pending" {{
                                        old('revison_batch_status')=='Pending' ? 'selected' : '' }} {{ $admin->
                                        revison_batch_status == 'Pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option style="background-color: orange;" value="Declined" {{
                                        old('revison_batch_status')=='Declined' ? 'selected' : '' }} {{ $admin->
                                        revison_batch_status == 'Declined' ? 'selected' : '' }}>
                                        Declined
                                    </option>
                                    <option style="background-color: green;" value="Approved" {{
                                        old('revison_batch_status')=='Approved' ? 'selected' : '' }} {{ $admin->
                                        revison_batch_status == 'Approved' ? 'selected' : '' }}>
                                        Approved
                                    </option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.stayawarebatch.index') }}"
                            class="btn btn-secondary mt-4 pr-4 pl-4">Back</a>

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
<!-- Bootstrap Modal -->
{{-- --}}
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $(".btn-close, .btn[data-bs-dismiss='modal']").click(function() {
            $("#statusModal").modal("hide");
            window.location.reload();
        });
    })
    // $('#statusModal').modal({
    //     backdrop: 'static',
    //     keyboard: true
    // })
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var select = document.getElementById("revison_batch_status");
        function updateBackgroundColor() {
            var selectedValue = select.value;
            $("#revison_batch_status").val(selectedValue);
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

    $("#approvalForm").submit(function(e) {
        e.preventDefault();
        var membership_id = $("#admin_id").val();
        $('#membership_id').val(membership_id);
        var url = "{{ url('/admin/membership/update') }}/" + membership_id;
        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function(response) {
                alert(response.message);
                $("#statusModal").modal("hide");
                window.location.reload();
            },
            error: function(xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });
</script>
@endsection