@extends('backend.layouts.master')

@section('title')
    Edit Gallery - Admin Panel
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Edit Gallery</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.gallery.index') }}">All Galleries</a></li>
                        <li><span>Edit Gallery - {{ $gallery->title }}</span></li>
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
                        <h4 class="header-title">Edit Gallery - {{ $gallery->title }}</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.gallery.update', ['id' => $gallery->id]) }}?type={{ $type }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $gallery->title }}" required>
                            </div>

                            <div class="form-group">
                                <label for="images">Gallery Image</label>
                                <input type="file" name="images" class="form-control" />
                                <br />
                                @php
                                    $folder = $type === 'ssu' ? 'ssu_gallery' : 'mmb_gallery';
                                @endphp
                                <img src="{{ asset($folder . '/' . $gallery->images) }}" alt="Gallery Image" width="100px" height="80px" />
                            </div>



                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary mt-3">Cancel</a>
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
        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>
@endsection
