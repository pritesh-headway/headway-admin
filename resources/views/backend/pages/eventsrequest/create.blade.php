@extends('backend.layouts.master')

@section('title')
Event Create - Admin Panel
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
                <h4 class="page-title pull-left">Event Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.event.index') }}">All Events</a></li>
                    <li><span>Create Blog</span></li>
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
                    <h4 class="header-title">Create New Event</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Event Name</label>
                                <input type="text" class="form-control" id="event_name" name="event_name"
                                    placeholder="Enter Name" required autofocus value="{{ old('event_name') }}"
                                    required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Event Building Name</label>
                                <input type="text" class="form-control" id="event_address" name="event_address"
                                    placeholder="Enter Building Name" value="" required autofocus>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Event Address</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    placeholder="Enter Address" required autofocus value="{{ old('location') }}"
                                    required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Description</label>
                                <textarea required class="form-control" id="description" name="description"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Event Price</label>
                                <input type="text" class="form-control" id="event_price" name="event_price"
                                    placeholder="Enter Price" required autofocus value="{{ old('event_price') }}"
                                    required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Event Date & Time</label>
                                <input type="text" class="form-control datetime" id="event_date_time"
                                    name="event_date_time" placeholder="" required autofocus
                                    value="{{ old('event_date_time') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            {{-- <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Image</label>
                                <input type="file" name="image" id="image" class="form-control" required />
                            </div> --}}
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Status</label>
                                <select class="form-control " id="status" name="status" required>
                                    <option value="1" {{ old('status')=='1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status')=='0' ? 'selected' : '' }}>Completed
                                    </option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.event.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
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