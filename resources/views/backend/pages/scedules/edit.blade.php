@extends('backend.layouts.master')

@section('title')
Banner Edit - Admin Panel
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
                <h4 class="page-title pull-left">Banner Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.banner.index') }}">All Banners</a></li>
                    <li><span>Edit Banner - {{ $admin->title }}</span></li>
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
                    <h4 class="header-title">Edit Admin - {{ $admin->title }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.banner.update', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Enter Title" value="{{ $admin->title }}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="email">Heading</label>
                                <input type="text" class="form-control" id="heading" name="heading"
                                    placeholder="Enter Heading" value="{{ $admin->heading }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="password">Description</label>
                            <textarea required class="form-control" id="desc" name="desc">{{ $admin->desc }}</textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Banner Image</label>
                                <input type="file" name="image" id="image" class="form-control" />
                                <br />
                                <img src="{{ asset('banners/'.$admin->image) }}" alt="Banner Image" width="100px"
                                    height="80px" />
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
                        <div class="form-row">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_popup" name="is_popup" {{
                                    $admin->is_popup=='1'
                                ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_popup">Popup Show on Web</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
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