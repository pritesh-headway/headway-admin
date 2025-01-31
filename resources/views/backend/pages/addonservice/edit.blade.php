@extends('backend.layouts.master')

@section('title')
Add On Service Edit - Admin Panel
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
                <h4 class="page-title pull-left">Add On Service Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.addonservice.index') }}">All Add On Services</a></li>
                    <li><span>Edit Add On Service - {{ $admin->title }}</span></li>
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
                    <h4 class="header-title">Edit Add On Service - {{ $admin->title }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.addonservice.update', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Plan Name</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Enter Plan Name" value="{{ $admin->title }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Price</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    placeholder="Enter Price" value="{{ $admin->price }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Duration (In Hrs)</label>
                                <input type="text" class="form-control" id="duration" name="duration"
                                    placeholder="Enter Duration" value="{{ $admin->duration }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Tax (In %)</label>
                                <input type="text" class="form-control" id="tax" name="tax" placeholder="Enter Tax"
                                    value="{{ $admin->tax }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Sort Description</label>
                                <textarea required class="form-control" id="sort_desc"
                                    name="sort_desc">{{ $admin->sort_desc }}</textarea>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Description</label>
                                <textarea class="form-control" id="description"
                                    name="description">{{ $admin->description }}</textarea>
                            </div>

                        </div>

                        <div class="form-row">
                            {{-- <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Plan Type</label>
                                <select class="form-control " id="plan_type" name="plan_type" required>
                                    <option value="Member Plan" {{ old('plan_type')=='Member Plan' ? 'selected' : '' }}
                                        {{ $admin->plan_type=='Member Plan'
                                        ? 'selected' : '' }}>
                                        Member Plan
                                    </option>
                                    <option value="Service Plan" {{ old('plan_type')=='Service Plan' ? 'selected' : ''
                                        }} {{ $admin->plan_type=='Service Plan'
                                        ? 'selected' : '' }}>Service Plan
                                    </option>

                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Plan Image</label>
                                <input type="file" name="image" id="image" class="form-control" />
                                <br />
                                <img src="{{ asset('plans/'.$admin->image) }}" alt="Plan Image" width="100px"
                                    height="80px" />
                            </div> --}}
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">No. Of Store Visit</label>
                                <input type="text" class="form-control" id="on_store_visit" name="on_store_visit"
                                    placeholder="Enter No. Of Store Visit" value="{{ $admin->on_store_visit }}" required
                                    autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Status</label>
                                <select class="form-control " id="status" name="status" required>
                                    <option value="1" {{ old('status')=='1' ? 'selected' : '' }} {{ $admin->status=='1'
                                        ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status')=='0' ? 'selected' : '' }} {{ $admin->status=='0'
                                        ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.addonservice.index') }}"
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