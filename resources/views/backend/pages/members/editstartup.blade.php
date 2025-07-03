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

    table {
        border-collapse: separate;
        border-spacing: 10px;
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
                    <h4 class="header-title">View Membership - {{ $admin->full_name }}</h4>
                    @include('backend.layouts.partials.messages')
                    <p></p>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Full Name: </label><br />
                            <b>{{ $admin->full_name }}</b>
                        </div>
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Reference By: </label><br />
                            <b>{{ $admin->reference_by }}</b>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Product Name: </label><br />
                            <b>{{ $plans->plan_name }}</b>
                        </div>
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Product Type: </label><br />
                            <b>{{ $plans->plan_type }}</b>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Product Price: </label></br>
                            <b>{{ $plans->price }}</b>
                        </div>
                        {{-- <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Product Validity: </label></br>
                            <b>{{ $plans->validity }}</b>
                        </div> --}}
                    </div>


                    <div class="tab-content mt-3">
                        @php
                        $name = 'service';

                        $serviceName = App\Models\StartupServiceModules::where(['status'=> 1])->get();
                        $active = 0 == 0 ? 'active' : '';
                        @endphp
                        <div class="tab-pane fade show {{ $active }}" id="{{ $name }}" role="tabpanel">
                            <h4>Service Details</h4>
                            <p></p>
                            <table class="responsive-table" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Module Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviceName as $i => $service)
                                    @php
                                    $mod = [];
                                    $mod = $dataGetNodules->where('startup_id', $service['id'])->first();
                                    // dd($mod['module_status']);
                                    $isReadOnly = !isset($mod) || $mod['module_status'] !='Completed' ? '' :
                                    'disabled="disabled"';
                                    $statusUpdate = ($mod && $mod['module_status'] == 'Completed') ? '' : 'Update
                                    Status';
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $service->title }}</td>
                                        <td>
                                            <input {{ $isReadOnly }} type="text" class="form-control date"
                                                id="date_{{ $i }}" name="module_date[]"
                                                value="{{ $mod ? $mod['date'] : '' }}">
                                        </td>
                                        <td>
                                            <input {{ $isReadOnly }} type="text" class="form-control time"
                                                id="time_{{ $i }}" name="module_time[]"
                                                value="{{ $mod ? $mod['time'] : '' }}">
                                        </td>
                                        <td>
                                            <select name="module_status[]" id="status_{{ $i }}" class="form-control">
                                                <option value="Pending" {{ $mod && $mod['module_status']=='Pending'
                                                    ? 'selected' : '' }}>Pending</option>
                                                <option value="Date Assign" {{ $mod &&
                                                    $mod['module_status']=='Date Assign' ? 'selected' : '' }}>Date
                                                    Assign
                                                </option>
                                                <option value="Completed" {{ $mod && $mod['module_status']=='Completed'
                                                    ? 'selected' : '' }}>Completed
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input {{ $isReadOnly }} type="text" class="form-control"
                                                id="remarks_{{ $i }}" name="module_remarks[]"
                                                value="{{ $mod ? $mod['remarks'] : '' }}">
                                        </td>
                                        <td>
                                            {{-- @if ($statusUpdate != '') --}}
                                            <button class="btn btn-primary" id="btn_{{ $i }}"
                                                onclick="addUpdateModule({{ $service->id }}, {{ $member_id }}, {{ $admin->product_id }}, {{ $i }});">
                                                {{ $mod ? 'Update' : 'Save' }}
                                            </button>
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
        var select = document.getElementById("membership_status");

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

        if(select) {
            // Run function on change
            select.addEventListener("change", updateBackgroundColor);
        }

        // Set initial color based on selected value
        updateBackgroundColor();
    });

    function addUpdateModule(moduleID, memberID, membershipID, i) {
        var date = $('#date_' + i).val();
        var time = $('#time_' + i).val();
        var status = $('#status_' + i).val();
        var remarks = $('#remarks_' + i).val();
        var trainer_id = $('#trainer_id_' + i).val();
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.members.addUpdateStartupModuleData') }}",
            data: {
                startupID: moduleID,
                memberID: memberID,
                membershipID:membershipID,
                date: date,
                trainer_id: 00,
                time: time,
                status: status,
                remarks: remarks,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.status) {
                    $("#btn_" + i).remove();
                    alert('Data saved successfully.!');
                    // toastr.success(response.message);
                } else {
                    // toastr.error(response.message);
                }
            }
        });
    }

</script>


<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".date", { dateFormat: "Y-m-d" });
    flatpickr(".time", { enableTime: true, noCalendar: true, dateFormat: "H:i" });
</script>
<script>
    CKEDITOR.replaceAll('description');
    setTimeout(() => {
        $('.cke_notification_warning').hide();
    }, 1000);
</script>
@endsection
