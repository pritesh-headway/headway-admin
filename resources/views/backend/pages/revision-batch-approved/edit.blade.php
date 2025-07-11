@extends('backend.layouts.master')

@section('title')
Revision Batch Approved Edit - Admin Panel
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
                <h4 class="page-title pull-left">Revision Batch Approved View</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.revisionbatchapproved.index') }}">All Revision Batch Approved</a></li>
                    <li><span>View Revision Batch Approved - {{ $admin->owner_name }}</span></li>

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
                    <h4 class="header-title">View Revision Batch Approved - {{ $admin->owner_name }}</h4>
                    @include('backend.layouts.partials.messages')
                    <p></p>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Owner Name: </label><br />
                            <b>{{ $admin->owner_name }}</b>
                        </div>
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Shop Name: </label><br />
                            <b>{{ $admin->shop_name }}</b>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Mobile Number: </label><br />
                            <b>{{ $admin->phone_number }}</b>
                        </div>
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Subject: </label><br />
                            <b>{{ $admin->subject }}</b>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Message: </label></br>
                            <b>{{ $admin->message }}</b>
                        </div>
                        {{-- <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Product Validity: </label></br>
                            <b>{{ $plans->validity }}</b>
                        </div> --}}
                    </div>


                    <div class="tab-content mt-3">
                        @php
                        $name = 'service';
                        $active = 0 == 0 ? 'active' : '';
                        @endphp
                        <div class="tab-pane fade show {{ $active }}" id="{{ $name }}" role="tabpanel">
                            <h4>Module Details</h4>
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
                                    @foreach ($modulesName as $i => $service)
                                    @php
                                    $mod = [];
                                    $mod = $dataGetNodules->where('module_id', $service['id'])->first();
                                    $isReadOnly = !isset($mod) || $mod['module_status'] !='Completed' ? '' :
                                    'disabled="disabled"';
                                    $statusUpdate = ($mod && $mod['module_status'] == 'Completed') ? '' : 'Update
                                    Status';
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $service->name }}</td>
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
                                            @if ($statusUpdate != '')
                                            <button class="btn btn-primary" id="btn_{{ $i }}"
                                                onclick="addUpdateModule({{ $service->id }}, {{ $member_id }}, {{ $admin->plan_id }}, {{ $i }});">
                                                {{ $mod ? $statusUpdate : 'Save' }}
                                            </button>
                                            @endif
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
    function addUpdateModule(moduleID, memberID, membershipID, i) {
        var date = $('#date_' + i).val();
        var time = $('#time_' + i).val();
        var status = $('#status_' + i).val();
        var remarks = $('#remarks_' + i).val();
        var trainer_id = $('#trainer_id_' + i).val();
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.revisionbatchapproved.addUpdateModuleData') }}",
            data: {
                module_id: moduleID,
                member_id: memberID,
                membership_id:membershipID,
                date: date,
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