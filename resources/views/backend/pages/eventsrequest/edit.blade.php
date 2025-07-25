@extends('backend.layouts.master')

@section('title')
Event Details - Admin Panel
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
                <h4 class="page-title pull-left">Request Event Details</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.eventrequest.index') }}">All Request Event</a></li>
                    <li><span>Detail Request Event - {{ $admin['Users']->name }}</span></li>
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
                    <h4 class="header-title">Request Event - {{ $admin['Users']->name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.eventrequest.update', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Event Name</label>
                                <input type="text" class="form-control" id="event_name" name="event_name"
                                    placeholder="Enter Event Name" value="{{ $admin['Events']->event_name }}" readonly
                                    required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Event Building Name</label>
                                <input type="text" class="form-control" id="event_address" name="event_address"
                                    placeholder="Enter Hour" value="{{ $admin['Events']->event_address }}" readonly
                                    required autofocus>
                            </div>


                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Event Address</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    placeholder="Enter Location" value="{{ $admin['Events']->location }}" readonly
                                    required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Description</label>
                                <textarea readonly class="form-control" id="description"
                                    name="description">{{ $admin['Events']->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Event Price</label>
                                <input type="text" class="form-control" readonly id="event_price" name="event_price"
                                    placeholder="Enter Price" required autofocus
                                    value="{{ $admin['Events']->event_price }}" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Event Date & Time</label>
                                <input type="text" readonly class="form-control " id="event_date_time"
                                    name="event_date_time" placeholder="Enter Price" required autofocus
                                    value="{{ $admin['Events']->event_date_time }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Status</label>
                                <select class="form-control " id="request_status" name="request_status" required>
                                    <option value="Pending" {{ old('request_status')=='Pending' ? 'selected' : '' }} {{
                                        $admin->request_status == 'Pending'
                                        ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="Requested" {{ old('request_status')=='Requested' ? 'selected' : '' }}
                                        {{ $admin->request_status == 'Requested'
                                        ? 'selected' : '' }}>Requested
                                    </option>
                                    <option value="Approved" {{ old('request_status')=='Approved' ? 'selected' : '' }}
                                        {{ $admin->request_status == 'Approved'
                                        ? 'selected' : '' }}>Approved
                                    </option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.eventrequest.index') }}"
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".date", { dateFormat: "Y-m-d" });
    flatpickr(".time", { enableTime: true, noCalendar: true, dateFormat: "H:i" });
    flatpickr(".datetime", {
    dateFormat: "Y-m-d H:i", // H:i = 24-hour format like 14:30
    enableTime: true
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection