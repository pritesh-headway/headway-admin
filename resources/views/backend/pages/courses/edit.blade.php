@extends('backend.layouts.master')

@section('title')
Courses Edit - Admin Panel
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
                <h4 class="page-title pull-left">Courses Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.courses.index') }}">All Courses</a></li>
                    <li><span>Edit Course - {{ $admin->name }}</span></li>
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
                    <h4 class="header-title">Edit Course - {{ $admin->name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.courses.update', $admin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Title</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Title"
                                    value="{{ $admin->name }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Description</label>
                                <textarea class="form-control" required id="description"
                                    name="info">{{ $admin->description }}</textarea>
                            </div>
                            {{-- <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Video Type</label>
                                <select class="form-control " id="type" name="type" required>
                                    <option value="Youtube" {{ $admin->type=='Youtube' ? 'selected' : '' }}>Youtube
                                    </option>
                                    <option value="Training" {{ $admin->type=='Training' ? 'selected' : '' }}>Training
                                    </option>
                                </select>
                            </div> --}}
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Video Url</label>
                                <input type="text" name="url" id="url" value="{{ $admin->video_url }}" required
                                    class="form-control" />
                                <br />

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

                        <button type="submit" id="sbtBts" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
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
    $('#url').bind("change keyup input", function() {
        var url = $(this).val();

        if (ytVidId(url) !== false) {
            $("#sbtBts").prop('disabled', false);
            // $('#url').css('border-color', 'green');
        } else {
            $("#sbtBts").prop('disabled', true);
            $('#url').css('border-color', 'red');
        }
    });
</script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('description');
    setTimeout(() => {
        $('.cke_notification_warning').hide();
    }, 1000);
</script>
@endsection