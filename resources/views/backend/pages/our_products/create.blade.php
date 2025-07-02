@extends('backend.layouts.master')

@section('title')
    Product Create - Admin Panel
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
                    <h4 class="page-title pull-left">Product Create</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.our_products.index') }}">All Products</a></li>
                        <li><span>Create Product</span></li>
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
            <!-- form start -->
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Create New Product</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.our_products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="title">Product Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Enter Title" required autofocus value="{{ old('title') }}">
                            </div>

                            <div class="form-group">
                                <label for="tagline">Product tagline</label>
                                <input type="text" class="form-control" id="tagline" name="tagline"
                                    placeholder="Enter tagline" required autofocus value="{{ old('tagline') }}">
                            </div>

                            <div class="form-group">
                                <label for="desc">Description</label>
                                <textarea class="form-control" id="desc" name="desc" rows="4" placeholder="Enter Description">{{ old('desc') }}</textarea>
                            </div>


                            <div class="form-group">
                                <label for="app_store">App Store URL</label>
                                <input type="url" class="form-control" id="app_store" name="app_store"
                                    placeholder="https://apps.apple.com/..." value="{{ old('app_store') }}">
                            </div>

                            <div class="form-group">
                                <label for="play_store">Play Store URL</label>
                                <input type="url" class="form-control" id="play_store" name="play_store"
                                    placeholder="https://play.google.com/..." value="{{ old('play_store') }}">
                            </div>

                            <div class="form-group">
                                <label for="web_url">Web URL</label>
                                <input type="url" class="form-control" id="web_url" name="web_url"
                                    placeholder="https://yourwebsite.com" value="{{ old('web_url') }}">
                            </div>


                            <div class="form-group">
                                <label for="photo">Product Image</label>
                                <input type="file" name="photo" id="photo" class="form-control" accept="image/*" />
                            </div>

                            <div class="form-group">
                                <label for="product_banner">Product Banner</label>
                                <input type="file" name="product_banner" id="product_banner" class="form-control"
                                    accept="image/*" />
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                            <a href="{{ route('admin.our_products.index') }}"
                                class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
            <!-- form end -->
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Initialize CKEditor
            CKEDITOR.replace('desc');
        });
    </script>
@endsection
