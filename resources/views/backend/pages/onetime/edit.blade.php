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
                <h4 class="page-title pull-left">One Time Request Schedule View</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.onemeetingrequest.index') }}">All One Time Request Schedule</a></li>
                    <li><span>One Time Request Schedule - {{ $admin->name }}</span></li>
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
                    <h4 class="header-title">View One Time Request Schedule - {{ $admin->name }}</h4>
                    @include('backend.layouts.partials.messages')
                    <p></p>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Full Name: </label><br />
                            <b>{{ $admin->name }}</b>
                        </div>
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Phone Number: </label><br />
                            <b>{{ $admin->phone_number }}</b>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Shop Name: </label><br />
                            <b>{{ $admin->shop_name }}</b>
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
                        // dd($admin);
                        $active = 0 == 0 ? 'active' : '';
                        $name = $admin['name'];
                        @endphp
                        <div class="tab-pane fade show {{ $active }}" id="{{ $name }}" role="tabpanel">
                            <h4>One Time Meeting Details</h4>
                            <p></p>
                            <table class="responsive-table" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    // dd($admin);
                                    $isReadOnly = !isset($admin) || $admin['call_status'] !='Completed' ? '' :
                                    'disabled="disabled"';
                                    $statusUpdate = ($admin && $admin['call_status'] == 'Completed') ? '' :
                                    'Update Status';
                                    $i = 1;
                                    @endphp
                                    <tr>
                                        <td>{{ $i}}</td>
                                        <td>
                                            <input {{ $isReadOnly }} type="text" class="form-control date"
                                                id="date_{{ $i }}" name="module_date[]"
                                                value="{{ $admin ? $admin['schedule_date'] : '' }}">
                                        </td>
                                        <td>
                                            <input {{ $isReadOnly }} type="text" class="form-control time"
                                                id="time_{{ $i }}" name="module_time[]"
                                                value="{{ $admin ? $admin['schedule_time'] : '' }}">
                                        </td>
                                        <td>
                                            <select name="module_status[]" id="status_{{ $i }}" class="form-control">
                                                <option value="Pending" {{ $admin && $admin['call_status']=='Pending'
                                                    ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="Date Assign" {{ $admin &&
                                                    $admin['call_status']=='Date Assign' ? 'selected' : '' }}>Date
                                                    Assign
                                                </option>
                                                <option value="Completed" {{ $admin &&
                                                    $admin['call_status']=='Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input {{ $isReadOnly }} type="text" class="form-control"
                                                id="remarks_{{ $i }}" name="module_remarks[]"
                                                value="{{ $admin ? $admin['remarks'] : '' }}">
                                        </td>
                                        <td>
                                            {{-- @if ($statusUpdate != '') --}}
                                            <button class="btn btn-primary" id="btn_{{ $i }}"
                                                onclick="addUpdateModule({{ $admin->id }}, {{ $admin->user_id }}, {{ $i }});">
                                                {{ $admin ? 'Update' : 'Save' }}
                                            </button>
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
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
    function addUpdateModule(meeting_id, user_id, i) {
        var date = $('#date_' + i).val();
        var time = $('#time_' + i).val();
        var status = $('#status_' + i).val();
        var remarks = $('#remarks_' + i).val();
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.onetimerequest.addUpdateOneTimeMeetingData') }}",
            data: {
                user_id: user_id,
                meeting_id:meeting_id,
                date: date,
                time: time,
                status: status,
                remarks: remarks,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.status) {
                    // $("#btn_" + i).remove();
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