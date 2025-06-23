@extends('backend.layouts.master')

@section('title')
    Gallery Create - Admin Panel
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Cropper CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />


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
                    <h4 class="page-title pull-left">Create Gallery - {{ strtoupper($type) }}</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.gallery.index') }}">All Galleries</a></li>
                        <li><span>Create {{ strtoupper($type) }} Gallery</span></li>
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
                        <h4 class="header-title">Create New Gallery Item</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.gallery.store') }}?type={{ $type }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Enter Title" required value="{{ old('title') }}">
                                </div>


                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="images">Gallery Image</label>
                                    <input type="file" name="images" id="imagesInput" class="form-control"
                                        accept="image/*" required />
                                </div>

                                <!-- Preview & Crop Modal -->
                                <div id="cropModal" style="display:none;">
                                    <img id="imagePreview" style="max-width: 100%;" />
                                    <button type="button" id="cropBtn" class="btn btn-success mt-2">Crop</button>
                                </div>

                                <input type="hidden" name="cropped_image" id="croppedImageInput">

                            </div>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
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
    <!-- Cropper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        let cropper;
        const imageInput = document.getElementById('imagesInput');
        const imagePreview = document.getElementById('imagePreview');
        const cropModal = document.getElementById('cropModal');
        const croppedImageInput = document.getElementById('croppedImageInput');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    cropModal.style.display = 'block';

                    // Wait for image to load
                    imagePreview.onload = function() {
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(imagePreview, {
                            aspectRatio: 233 / 117, // 1.99
                            viewMode: 1,
                        });
                    };
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('cropBtn').addEventListener('click', function() {
            const canvas = cropper.getCroppedCanvas({
                width: 800, // Optional: control output size
                height: 402,
            });

            canvas.toBlob(function(blob) {
                const file = new File([blob], 'cropped.jpg', {
                    type: 'image/jpeg'
                });

                // Create a temporary FormData to convert to base64
                const reader = new FileReader();
                reader.onloadend = function() {
                    croppedImageInput.value = reader.result;
                };
                reader.readAsDataURL(file);
            });

            cropModal.style.display = 'none';
        });
    </script>
@endsection
