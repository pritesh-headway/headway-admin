@extends('backend.layouts.master')

@section('title')
    Edit Product - Admin Panel
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
                    <h4 class="page-title pull-left">Edit Product</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.our_products.index') }}">All Products</a></li>
                        <li><span>Edit Product - {{ $product->title }}</span></li>
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
                        <h4 class="header-title">Edit Product - {{ $product->title }}</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.our_products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label for="title">Product Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Enter Title" value="{{ old('title', $product->title) }}" required
                                    autofocus>
                            </div>

                            <div class="form-group">
                                <label for="tagline">Product tagline</label>
                                <input type="text" class="form-control" id="tagline" name="tagline"
                                    placeholder="Enter tagline" value="{{ old('tagline', $product->tagline) }}" required
                                    autofocus>
                            </div>

                            <div class="form-group">
                                <label for="desc">Description</label>
                                <textarea class="form-control" id="desc" name="desc" rows="6" placeholder="Enter Description">{{ old('desc', $product->desc) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="app_store">App Store URL</label>
                                <input type="url" class="form-control" id="app_store" name="app_store"
                                    value="{{ old('app_store', $product->app_store) }}">
                            </div>

                            <div class="form-group">
                                <label for="play_store">Play Store URL</label>
                                <input type="url" class="form-control" id="play_store" name="play_store"
                                    value="{{ old('play_store', $product->play_store) }}">
                            </div>

                            <div class="form-group">
                                <label for="web_url">Web URL</label>
                                <input type="url" class="form-control" id="web_url" name="web_url"
                                    value="{{ old('web_url', $product->web_url) }}">
                            </div>

                            <div class="form-group">
                                <label for="image">Product Image</label>
                                <input type="file" name="photo" id="photo" class="form-control" />
                                <br />
                                @if ($product->photo)
                                    <img src="{{ asset('products/' . $product->photo) }}" alt="Product Image"
                                        width="100px" height="80px" />
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="product_banner">Product Banner</label>
                                <input type="file" name="product_banner" id="product_banner" class="form-control" />
                                <br />
                                @if ($product->product_banner)
                                    <img src="{{ asset('products/' . $product->product_banner) }}" alt="Product Banner"
                                        width="100px" height="80px" />
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>
                                        Inactive</option>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize CKEditor
            CKEDITOR.replace('desc');

            // Initialize Select2
            $('.select2').select2();
        });
    </script>
@endsection
