@extends('backend.layouts.master')

@section('title')
Membership Edit - Admin Panel
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
                <h4 class="page-title pull-left">Membership View</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.membership.index') }}">All Memberships</a></li>
                    <li><span>View Membership - {{ $admin->full_name }}</span></li>
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
                    <b>Membership Name:</b>
                    <h4 class="header-title">{{ $plans->plan_name }}</h4>
                    <b>Batch Assigned:</b>
                    <h4 class="header-title">{{ ($batchDetails->batch)?? 'Pending' }}</h4>
                    @include('backend.layouts.partials.messages')

                    {{-- <form action="{{ route('admin.membership.update', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf --}}
                        <div class="form-row">
                            <input type="hidden" name="admin_id" id="admin_id" value="{{ $admin->id }}">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name"
                                    placeholder="Enter Full Name" value="{{ $admin->full_name }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Reference By</label>
                                <input type="text" class="form-control" id="reference_by" name="reference_by"
                                    placeholder="Enter Reference By" value="{{ $admin->reference_by }}" readonly
                                    autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Product Name</label>
                                <input type="text" class="form-control" id="plan_name" name="plan_name"
                                    placeholder="Enter Gender" value="{{ $plans->plan_name }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Product Type</label>
                                <input type="text" class="form-control" id="plan_type" name="plan_type"
                                    placeholder="Enter Gender" value="{{ $plans->plan_type }}" readonly autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Product Price</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    placeholder="Enter Gender" value="{{ $plans->price }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Product Validity</label>
                                <input type="text" class="form-control" id="validity" name="validity"
                                    placeholder="Enter Gender" value="{{ $plans->validity }}" readonly autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Gender</label>
                                <input type="text" class="form-control" id="gender" name="gender"
                                    placeholder="Enter Gender" value="{{ $admin->gender }}" readonly autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Date Of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                                    value="{{ $admin->date_of_birth }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Qualification</label>
                                <input type="text" name="qualification" id="qualification" class="form-control"
                                    value="{{ $admin->qualification }}" readonly />
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Occupation</label>
                                <input type="text" name="occupation" id="occupation" class="form-control"
                                    value="{{ $admin->occupation }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Designation</label>
                                <input type="text" name="designation" id="designation" class="form-control"
                                    value="{{ $admin->designation }}" readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Mobile No</label>
                                <input type="text" name="mobile_no" id="mobile_no" class="form-control"
                                    value="{{ $admin->mobile_no }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Email ID</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    value="{{ $admin->email }}" readonly />
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Date Of Anniversary</label>
                                <input type="date" name="date_of_anniversary" id="date_of_anniversary"
                                    class="form-control" value="{{ $admin->date_of_anniversary }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Nationality</label>
                                <input type="text" name="nationality" id="nationality" class="form-control"
                                    value="{{ $admin->nationality }}" readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Address</label>
                                <input type="text" name="address" id="address" class="form-control"
                                    value="{{ $admin->address }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">City</label>
                                <input type="text" name="city" id="city" class="form-control" value="{{ $admin->city }}"
                                    readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">State</label>
                                <input type="text" name="state" id="state" class="form-control"
                                    value="{{ $admin->state }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Pincode</label>
                                <input type="text" name="pincode" id="pincode" class="form-control"
                                    value="{{ $admin->pincode }}" readonly />

                            </div>
                            {{-- <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Landline No</label>
                                <input type="text" name="landline_no" id="landline_no" class="form-control"
                                    value="{{ $admin->landline_no }}" readonly />
                            </div> --}}
                        </div>

                        {{-- <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Contact Person Name</label>
                                <input type="text" name="contact_person_name" id="contact_person_name"
                                    class="form-control" value="{{ $admin->contact_person_name }}" readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Contact Person Mobile</label>
                                <input type="text" name="contact_person_mobile" id="contact_person_mobile"
                                    class="form-control" value="{{ $admin->contact_person_mobile }}" readonly />
                            </div>
                        </div> --}}

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Organization Name</label>
                                <input type="text" name="contact_person_name" id="organization_name"
                                    class="form-control" value="{{ $admin->organization_name }}" readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Bussiness City</label>
                                <input type="text" name="bussiness_city" id="bussiness_city" class="form-control"
                                    value="{{ $admin->bussiness_city }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Organization Name</label>
                                <input type="text" name="organization_name" id="organization_name" class="form-control"
                                    value="{{ $admin->organization_name }}" readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Bussiness City</label>
                                <input type="text" name="bussiness_city" id="bussiness_city" class="form-control"
                                    value="{{ $admin->bussiness_city }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Bussiness State</label>
                                <input type="text" name="bussiness_state" id="bussiness_state" class="form-control"
                                    value="{{ $admin->bussiness_state }}" readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Bussiness Pincode</label>
                                <input type="text" name="bussiness_pincode" id="bussiness_pincode" class="form-control"
                                    value="{{ $admin->bussiness_pincode }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Registered Office Address</label>
                                <input type="text" name="registered_office_address" id="registered_office_address"
                                    class="form-control" value="{{ $admin->registered_office_address }}" readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Bussiness Email</label>
                                <input type="text" name="bussiness_email" id="bussiness_email" class="form-control"
                                    value="{{ $admin->bussiness_email }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            {{-- <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Bussiness Landline No</label>
                                <input type="text" name="bussiness_landline_no" id="bussiness_landline_no"
                                    class="form-control" value="{{ $admin->bussiness_landline_no }}" readonly />

                            </div> --}}
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">GST No</label>
                                <input type="text" name="gst_no" id="gst_no" class="form-control"
                                    value="{{ $admin->gst_no }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">PAN No</label>
                                <input type="text" name="pan_no" id="pan_no" class="form-control"
                                    value="{{ $admin->pan_no }}" readonly />

                            </div>
                            {{-- <div class="form-group col-md-6 col-sm-6">
                                <label for="password">FAX No</label>
                                <input type="text" name="fax_no" id="fax_no" class="form-control"
                                    value="{{ $admin->fax_no }}" readonly />
                            </div> --}}
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Date Of Incorporation</label>
                                <input type="date" name="date_of_incorporation" id="date_of_incorporation"
                                    class="form-control" value="{{ $admin->date_of_incorporation }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Organization Type</label>
                                <input type="text" name="organization_type" id="organization_type" class="form-control"
                                    value="{{ $admin->organization_type }}" readonly />
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Payment Receipt</label>
                                {{-- <input type="file" name="payment_receipt" id="payment_receipt"
                                    class="form-control" /> --}}
                                <br />
                                <a href="{{ asset('payment_receipts/'.$admin->payment_receipt) }}" target="_blank"><img
                                        src="{{ asset('payment_receipts/'.$admin->payment_receipt) }}" alt="Blog Image"
                                        width="150px" height="100px" /></a>
                            </div>
                        </div>

                        {{-- <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Bank Name</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control"
                                    value="{{ $admin->bank_name }}" readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Account No</label>
                                <input type="text" name="account_no" id="account_no" class="form-control"
                                    value="{{ $admin->account_no }}" readonly />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Account Operation Since</label>
                                <input type="text" name="account_operation_since" id="account_operation_since"
                                    class="form-control" value="{{ $admin->account_operation_since }}" readonly />

                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Branch Address</label>
                                <input type="text" name="branch_address" id="branch_address" class="form-control"
                                    value="{{ $admin->branch_address }}" readonly />
                            </div>
                        </div> --}}

                        <div class="form-row">
                            {{-- <div class="form-group col-md-6 col-sm-6">
                                <label for="password">IFSC Code</label>
                                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control"
                                    value="{{ $admin->ifsc_code }}" readonly />

                            </div> --}}

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
                                <label for="username">Membership Status</label>
                                <select class="form-control " id="membership_status" name="membership_status">
                                    <option style="background-color: red;" value="Pending" {{
                                        old('membership_status')=='Pending' ? 'selected' : '' }} {{ $admin->
                                        membership_status=='Pending'
                                        ? 'selected' : '' }}>Pending
                                    </option>
                                    <option style="background-color: orange;" value="Declined" {{
                                        old('membership_status')=='Declined' ? 'selected' : '' }} {{ $admin->
                                        membership_status=='Declined'
                                        ? 'selected' : '' }}>Declined
                                    </option>
                                    <option style="background-color: green;" value="Approved" {{
                                        old('membership_status')=='Approved' ? 'selected' : '' }} {{ $admin->
                                        membership_status=='Approved'
                                        ? 'selected' : '' }}>Approved
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button> --}}
                        <a href="{{ route('admin.membership.index') }}"
                            class="btn btn-secondary mt-4 pr-4 pl-4">Back</a>
                        {{--
                    </form> --}}
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
<div class="modal fade" id="statusModal" tabindex="-1" data-keyboard="false" data-backdrop="static"
    aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php $rand = rand(100, 999); ?>
                <h5 class="modal-title">Headway ID - {{ $rand }}</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                <span style="cursor: pointer;font-size: 21px;" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close" aria-hidden="true">&times;</span>
            </div>
            <div class="modal-body">
                <form id="approvalForm">
                    <input type="hidden" name="membership_id" id="membership_id">
                    <input type="hidden" name="membership_statuss" id="membership_statuss">
                    <input type="hidden" name="headway_id" id="headway_id" value="{{ $rand }}">
                    <div class="mb-3">
                        <label for="batch_no" class="form-label">Select Batch No.</label>
                        <select class="form-select form-control" name="batch_no" id="batch_no" required>
                            <option value="">Choose Batch...</option>
                            @foreach ($batches as $batch)
                            <option value="{{ $batch->batch_no }}">{{ $batch->batch_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" style="text-align: center;">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-secondary" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $("#membership_status").change(function () {
        var status = $(this).val();
        if (status === "Approved") {
            $("#statusModal").modal("show");
        }
    });
    $(document).ready(function() {
        $('.select2').select2();
        $(".btn-close, .btn[data-bs-dismiss='modal']").click(function () {
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
        var select = document.getElementById("membership_status");
        function updateBackgroundColor() {
            var selectedValue = select.value;
            $("#membership_statuss").val(selectedValue);
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

    $("#approvalForm").submit(function (e) {
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
            success: function (response) {
                alert(response.message);
                $("#statusModal").modal("hide");
                window.location.reload();
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });
</script>
@endsection