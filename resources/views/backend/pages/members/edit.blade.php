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
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="name">Product Validity: </label></br>
                            <b>{{ $plans->validity }}</b>
                        </div>
                    </div>
                    <ul class="nav nav-tabs" id="membershipTab" role="tablist">

                        @foreach ($modulesName as $key => $modules)
                        @php
                        $nameS = str_replace([' ', '&'], '_', $modules->title);
                        $active = $key == 0 ? 'active' : '';
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $active }}" id="{{ $nameS }}-tab" data-toggle="tab"
                                href="#{{ $nameS }}" role="tab">{{ $modules->title }} </a>
                        </li>
                        @endforeach
                    </ul>

                    <div class="tab-content mt-3">

                        @foreach ($modulesName as $key => $module)
                        @php
                        $name = str_replace([' ', '&'], '_', $module->title);
                        $session = $module->session;
                        $moduleID = $module->id;
                        $serviceName = App\Models\Modules::where(['status'=> 1,'service_id' =>
                        $module->id])->get();
                        $active = $key == 0 ? 'active' : '';
                        @endphp
                        <div class="tab-pane fade show {{ $active }}" id="{{ $name }}" role="tabpanel">
                            <h4>{{ $module->title }} Details</h4>
                            <p></p>

                            @if ($name == 'Module')
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
                                    $mod = $dataGetNodules->where('module_id', $service['id'])->first();
                                    $isReadOnly = !isset($mod) ? '' : 'disabled';
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
                                                onclick="addUpdateModuleData({{ $service->id }}, {{ $member_id }}, {{ $admin->product_id }}, {{ $i }});">
                                                {{ $mod ? $statusUpdate : 'Save' }}
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @elseif ($name == 'Workbooks')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="Workbooks">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @elseif ($name == 'Limited_on_call_team_support')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="Limited_on_call_team_support">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @elseif ($name == 'Beginner_Plan')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="Beginner_Plan">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @elseif ($name == 'Meetings_With_CMD')

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
                                    $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                                    if($visitData) {
                                    $visitData = array_values($visitData);
                                    $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                                    }
                                    $visitNo = 7000000;
                                    $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                                    $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                                    'Completed') ?
                                    '' : 'Update
                                    Status';
                                    @endphp
                                    @for ($i = 1; $i < $session+1; $i++) @php $visitNo=$visitNo + $i; @endphp <tr>
                                        <td>{{ $i }}</td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control date"
                                                id="date_{{ $visitNo }}" name="module_date[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['date'] : '' }}">
                                        </td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control time"
                                                id="time_{{ $visitNo }}" name="module_time[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['time'] : '' }}">
                                        </td>
                                        <td>
                                            <select name="module_status[]" id="status_{{ $visitNo }}"
                                                class="form-control">
                                                <option value="Pending" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="Date Assign" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Date Assign' ? 'selected' : '' }}>
                                                    Date
                                                    Assign
                                                </option>
                                                <option value="Completed" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        </td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control"
                                                id="remarks_{{ $visitNo }}" name="module_remarks[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['remarks'] : '' }}">
                                        </td>
                                        <td>
                                            @if ($statusUpdate != '')
                                            <button class="btn btn-primary"
                                                onclick="addUpdateModule({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }}, {{ $visitNo }});">{{
                                                isset($visitData[$i]) ? $statusUpdate : 'Save' }}</button>
                                            @endif
                                        </td>
                                        </tr>
                                        @endfor
                                </tbody>
                            </table>
                            @elseif ($name == 'Review_Training')
                            <table class="responsive-table" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Trainer Assign</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                                    if($visitData) {
                                    $visitData = array_values($visitData);
                                    $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                                    }
                                    $visitNo = 6000000;
                                    $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                                    $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                                    'Completed') ? '' : 'Update Status';
                                    @endphp
                                    @for ($i = 1; $i < $session+1; $i++) @php $visitNo=$visitNo + $i; @endphp <tr>
                                        <td>{{ $i }}</td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control date"
                                                id="date_{{ $visitNo }}" name="module_date[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['date'] : '' }}">
                                        </td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control time"
                                                id="time_{{ $visitNo }}" name="module_time[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['time'] : '' }}">
                                        </td>
                                        <td>
                                            <select class="form-control" name="trainer_assign[]"
                                                id="trainer_id_{{ $visitNo }}">
                                                <option value="">Select Trainer</option>
                                                @foreach ($trainers as $trainer)
                                                <option value="{{ $trainer->id }}" {{
                                                    isset($visitData[$i]['trainer_id']) &&
                                                    $visitData[$i]['trainer_id']==$trainer->id ? 'selected' : '' }}>
                                                    {{ $trainer->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="module_status[]" id="status_{{ $visitNo }}"
                                                class="form-control">
                                                <option value="Pending" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="Date Assign" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Date Assign' ? 'selected' : '' }}>
                                                    Date
                                                    Assign
                                                </option>
                                                <option value="Completed" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        </td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control"
                                                id="remarks_{{ $visitNo }}" name="module_remarks[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['remarks'] : '' }}">
                                        </td>
                                        <td>
                                            @if ($statusUpdate != '')
                                            <button class="btn btn-primary"
                                                onclick="addUpdateModule({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }}, {{ $visitNo }});">{{
                                                isset($visitData[$i]) ?
                                                $statusUpdate : 'Save' }}</button>
                                            @endif
                                        </td>
                                        </tr>
                                        @endfor
                                </tbody>
                            </table>

                            @elseif ($name == 'Theory___Practical')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="Theory___Practical">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @elseif ($name == 'On_Call_Team_Support')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="On_call_team_support">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @elseif ($name == 'Personal_Plan')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="Personal_Plan">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @elseif ($name == 'Meetings_at_HBS_office')

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
                                    $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                                    if($visitData) {
                                    $visitData = array_values($visitData);
                                    $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                                    }
                                    $visitNo = 5000000;
                                    $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                                    $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                                    'Completed') ?
                                    '' : 'Update
                                    Status';
                                    @endphp
                                    @for ($i = 1; $i < $session+1; $i++) @php $visitNo=$visitNo + $i; @endphp <tr>
                                        <td>{{ $i }}</td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control date"
                                                id="date_{{ $visitNo }}" name="module_date[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['date'] : '' }}">
                                        </td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control time"
                                                id="time_{{ $visitNo }}" name="module_time[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['time'] : '' }}">
                                        </td>
                                        <td>
                                            <select name="module_status[]" id="status_{{ $visitNo }}"
                                                class="form-control">
                                                <option value="Pending" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="Date Assign" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Date Assign' ? 'selected' : '' }}>
                                                    Date
                                                    Assign
                                                </option>
                                                <option value="Completed" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        </td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control"
                                                id="remarks_{{ $visitNo }}" name="module_remarks[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['remarks'] : '' }}">
                                        </td>
                                        <td>
                                            @if ($statusUpdate != '')
                                            <button class="btn btn-primary"
                                                onclick="addUpdateModule({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }}, {{ $visitNo }});">{{
                                                isset($visitData[$i]) ?
                                                $statusUpdate : 'Save' }}</button>
                                            @endif
                                        </td>
                                        </tr>
                                        @endfor
                                </tbody>
                            </table>
                            @elseif ($name == 'Online_Trainings')
                            <table class="responsive-table" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Trainer Assign</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                                    if($visitData) {
                                    $visitData = array_values($visitData);
                                    $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                                    }
                                    $visitNo = 4000000;
                                    $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                                    $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                                    'Completed') ?
                                    '' : 'Update
                                    Status';
                                    @endphp
                                    @for ($i = 1; $i < $session+1; $i++) @php $visitNo=$visitNo + $i; @endphp <tr>
                                        <td>{{ $i }}</td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control date"
                                                id="date_{{ $visitNo }}" name="module_date[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['date'] : '' }}">
                                        </td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control time"
                                                id="time_{{ $visitNo }}" name="module_time[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['time'] : '' }}">
                                        </td>
                                        <td>
                                            <select class="form-control" name="trainer_assign[]"
                                                id="trainer_id_{{ $visitNo }}">
                                                <option value="">Select Trainer</option>
                                                @foreach ($trainers as $trainer)
                                                <option value="{{ $trainer->id }}" {{
                                                    isset($visitData[$i]['trainer_id']) &&
                                                    $visitData[$i]['trainer_id']==$trainer->id ?
                                                    'selected' : '' }}>
                                                    {{ $trainer->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="module_status[]" id="status_{{ $visitNo }}"
                                                class="form-control">
                                                <option value="Pending" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="Date Assign" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Date Assign' ? 'selected' : '' }}>
                                                    Date
                                                    Assign
                                                </option>
                                                <option value="Completed" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        </td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control"
                                                id="remarks_{{ $visitNo }}" name="module_remarks[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['remarks'] : '' }}">
                                        </td>
                                        <td>
                                            @if ($statusUpdate != '')
                                            <button class="btn btn-primary"
                                                onclick="addUpdateModule({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }}, {{ $visitNo }});">{{
                                                isset($visitData[$i]) ?
                                                $statusUpdate : 'Save' }}</button>
                                            @endif
                                        </td>
                                        </tr>
                                        @endfor
                                </tbody>
                            </table>
                            @elseif ($name == 'Online_Reviews')

                            <table class="responsive-table" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Trainer Assign</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                                    if($visitData) {
                                    $visitData = array_values($visitData);
                                    $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                                    }
                                    $visitNo = 3000000;
                                    $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                                    $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                                    'Completed') ?
                                    '' : 'Update
                                    Status';
                                    @endphp
                                    @for ($i = 1; $i < $session+1; $i++) @php $visitNo=$visitNo + $i; @endphp <tr>
                                        <td>{{ $i }}</td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control date"
                                                id="date_{{ $visitNo }}" name="module_date[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['date'] : '' }}">
                                        </td>
                                        <td><input type="text" {{ $isReadOnly }} class="form-control time"
                                                id="time_{{ $visitNo }}" name="module_time[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['time'] : '' }}">
                                        </td>
                                        <td>
                                            <select class="form-control" name="trainer_assign[]"
                                                id="trainer_id_{{ $visitNo }}">
                                                <option value="">Select Trainer</option>
                                                @foreach ($trainers as $trainer)
                                                <option value="{{ $trainer->id }}" {{
                                                    isset($visitData[$i]['trainer_id']) &&
                                                    $visitData[$i]['trainer_id']==$trainer->id ?
                                                    'selected' : '' }}>
                                                    {{ $trainer->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="module_status[]" id="status_{{ $visitNo }}"
                                                class="form-control">
                                                <option value="Pending" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="Date Assign" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Date Assign' ? 'selected' : '' }}>
                                                    Date
                                                    Assign
                                                </option>
                                                <option value="Completed" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        </td>
                                        <td><input type="text" {{ $isReadOnly }} class="form-control"
                                                id="remarks_{{ $visitNo }}" name="module_remarks[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['remarks'] : '' }}">
                                        </td>
                                        <td>
                                            @if ($statusUpdate != '')
                                            <button class="btn btn-primary"
                                                onclick="addUpdateModule({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }}, {{ $visitNo }});">{{
                                                isset($visitData[$i]) ?
                                                $statusUpdate : 'Save' }}</button>
                                            @endif
                                        </td>
                                        </tr>
                                        @endfor
                                </tbody>
                            </table>
                            @elseif ($name == 'Bussiness_Plan')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="Bussiness_Plan">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @elseif ($name == 'Staff_Training')

                            <table class="responsive-table" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Trainer Assign</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                                    if($visitData) {
                                    $visitData = array_values($visitData);
                                    $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                                    }
                                    $visitNo = 2000000;
                                    $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                                    $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                                    'Completed') ? '' : 'Update Status';
                                    @endphp
                                    @for ($i = 1; $i < $session+1; $i++) @php $visitNo=$visitNo + $i; @endphp <tr>
                                        <td>{{ $i }}</td>
                                        <td><input type="text" {{ $isReadOnly }} class="form-control date"
                                                id="date_{{ $visitNo }}" name="module_date[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['date'] : '' }}">
                                        </td>
                                        <td><input type="text" {{ $isReadOnly }} class="form-control time"
                                                id="time_{{ $visitNo }}" name="module_time[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['time'] : '' }}">
                                        </td>
                                        <td>
                                            <select class="form-control" name="trainer_assign[]"
                                                id="trainer_id_{{ $visitNo }}">
                                                <option value="">Select Trainer</option>
                                                @foreach ($trainers as $trainer)
                                                <option value="{{ $trainer->id }}" {{
                                                    isset($visitData[$i]['trainer_id']) &&
                                                    $visitData[$i]['trainer_id']==$trainer->id ?
                                                    'selected' : '' }}>
                                                    {{ $trainer->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="module_status[]" id="status_{{ $visitNo }}"
                                                class="form-control">
                                                <option value="Pending" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="Date Assign" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Date Assign' ? 'selected' : '' }}>
                                                    Date
                                                    Assign
                                                </option>
                                                <option value="Completed" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        </td>
                                        <td><input type="text" {{ $isReadOnly }} class="form-control"
                                                id="remarks_{{ $visitNo }}" name="module_remarks[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['remarks'] : '' }}">
                                        </td>
                                        <td>
                                            @if ($statusUpdate != '')
                                            <button class="btn btn-primary"
                                                onclick="addUpdateModule({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }}, {{ $visitNo }});">{{
                                                isset($visitData[$i]) ?
                                                $statusUpdate : 'Save' }}
                                            </button>
                                            @endif
                                        </td>
                                        </tr>
                                        @endfor
                                </tbody>
                            </table>
                            @elseif ($name == 'Organization_Plan')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="Organization_Plan">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>

                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @elseif ($name == 'CMD_Visit')
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
                                    $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                                    if($visitData) {
                                    $visitData = array_values($visitData);
                                    $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                                    }
                                    $visitNo = 1000000;
                                    $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                                    $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                                    'Completed') ? '' : 'Update Status';
                                    @endphp
                                    @for ($i = 1; $i < $session+1; $i++) @php $visitNo=$visitNo + $i; @endphp <tr>
                                        <td>{{ $i }}</td>
                                        <td><input type="text" {{ $isReadOnly }} class="form-control date"
                                                id="date_{{ $visitNo }}" name="module_date[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['date'] : '' }}">
                                        </td>
                                        <td><input type="text" {{ $isReadOnly }} class="form-control time"
                                                id="time_{{ $visitNo }}" name="module_time[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['time'] : '' }}">
                                        </td>
                                        <td>
                                            <select name="module_status[]" id="status_{{ $visitNo }}"
                                                class="form-control">
                                                <option value="Pending" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="Date Assign" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Date Assign' ? 'selected' : '' }}>
                                                    Date
                                                    Assign
                                                </option>
                                                <option value="Completed" {{ isset($visitData[$i]) &&
                                                    $visitData[$i]['module_status']=='Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        </td>
                                        <td><input {{ $isReadOnly }} type="text" class="form-control"
                                                id="remarks_{{ $visitNo }}" name="module_remarks[]"
                                                value="{{ isset($visitData[$i]) ? $visitData[$i]['remarks'] : '' }}">
                                        </td>
                                        <td>
                                            @if ($statusUpdate != '')
                                            <button class="btn btn-primary"
                                                onclick="addUpdateModule({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }}, {{ $visitNo }});">{{
                                                isset($visitData[$i]) ?
                                                $statusUpdate : 'Save' }}
                                            </button>
                                            @endif
                                        </td>
                                        </tr>
                                        @endfor
                                </tbody>
                            </table>
                            @elseif ($name == 'Data_Processing_Support')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="Data_Processing_Support">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @elseif ($name == 'SOP')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="SOP">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>

                            @elseif ($name == 'SDF')
                            @php
                            $visitData = $dataGetNodules->where('module_id', $moduleID)->toArray();
                            if($visitData) {
                            $visitData = array_values($visitData);
                            $visitData = array_combine(range(1, count($visitData)), array_values($visitData));
                            }
                            $isReadOnly = !isset($visitData[$i]) ? '' : 'disabled';
                            $statusUpdate = (isset($visitData[$i]) && $visitData[$i]['module_status'] ==
                            'Completed') ? '' : 'Update Status';
                            @endphp
                            <textarea class="form-control description" id="description_{{ $moduleID }}"
                                name="SDF">{{ ($visitData) ? $visitData[1]['description'] : '' }}</textarea>
                            <button class="btn btn-primary" style="margin-top: 1%;"
                                onclick="addUpdateModuleText({{ $moduleID }}, {{ $member_id }}, {{ $admin->product_id }});">{{
                                isset($visitData[$i]) ?
                                $statusUpdate : 'Save' }}
                            </button>
                            @endif
                        </div>
                        @endforeach
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

        // Run function on change
        select.addEventListener("change", updateBackgroundColor);

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
            url: "{{ route('admin.members.addUpdateModuleData') }}",
            data: {
                serviceID: moduleID,
                memberID: memberID,
                membershipID:membershipID,
                date: date,
                trainer_id: trainer_id,
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
<script>
    function addUpdateModuleData(serviceID, memberID, membershipID, i) {
        var date = $('#date_' + i).val();
        var time = $('#time_' + i).val();
        var status = $('#status_' + i).val();
        var remarks = $('#remarks_' + i).val();
        var trainer_id = $('#trainer_id_' + i).val();
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.members.addUpdateModuleData') }}",
            data: {
                serviceID: serviceID,
                memberID: memberID,
                membershipID:membershipID,
                trainer_id: trainer_id,
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
<script>
    function addUpdateModuleText(serviceID, memberID, membershipID) {

        var remarks = CKEDITOR.instances['description_'+ serviceID].getData();
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.members.addUpdateModuleDataText') }}",
            data: {
                serviceID: serviceID,
                memberID: memberID,
                membershipID:membershipID,
                remarks: remarks,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.status) {
                    $("#btn_").remove();
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