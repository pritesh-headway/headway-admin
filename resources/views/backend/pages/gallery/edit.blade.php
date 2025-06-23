@extends('backend.layouts.master')

@section('title')
    Edit Gallery - Admin Panel
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />

    <style>
        #imagePreview {
            display: none;
            border: 1px solid #ccc;
            padding: 5px;
            /* max-width: 400px; */
            /* Limit width */
            /* max-height: 350px; */
            /* Limit height */
            width: 100%;
            /* Responsive within the limit */
            object-fit: contain;
        }
    </style>
@endsection

@section('admin-content')
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

    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Edit Gallery - {{ $gallery->title }}</h4>
                        @include('backend.layouts.partials.messages')

                        <form id="galleryForm"
                            action="{{ route('admin.gallery.update', ['id' => $gallery->id]) }}?type={{ $type }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ $gallery->title }}" required>
                            </div>

                            <div class="form-group">
                                <label for="imagesInput">Gallery Image</label>
                                <input type="file" id="imagesInput" class="form-control" accept="image/*" />
                                <input type="hidden" name="cropped_image" id="croppedImageInput">

                                <br />
                                @php
                                    $folder = $type === 'ssu' ? 'ssu_gallery' : 'mmb_gallery';
                                @endphp
                                <img src="{{ asset($folder . '/' . $gallery->images) }}" alt="Current Image" width="100px"
                                    height="80px" />
                            </div>

                            <div id="cropContainer"
                                style="max-width: 600px; max-height: 350px; overflow: hidden; margin-top: 20px;">
                                <img id="imagePreview" />
                            </div>


                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary mt-3">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        let cropper;
        const imageInput = document.getElementById('imagesInput');
        const imagePreview = document.getElementById('imagePreview');
        const croppedImageInput = document.getElementById('croppedImageInput');
        const form = document.getElementById('galleryForm');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                imagePreview.src = event.target.result;
                imagePreview.style.display = 'block';

                imagePreview.onload = function() {
                    if (cropper) cropper.destroy();

                    cropper = new Cropper(imagePreview, {
                        aspectRatio: 233 / 117,
                        viewMode: 1,
                        autoCropArea: 1,
                        background: false,
                    });
                };
            };
            reader.readAsDataURL(file);
        });

        form.addEventListener('submit', function(e) {
            // If cropper exists (new image selected), intercept submit
            if (cropper) {
                e.preventDefault();

                const canvas = cropper.getCroppedCanvas({
                    width: 800,
                    height: Math.round(800 * (117 / 233))
                });

                canvas.toBlob(function(blob) {
                    const reader = new FileReader();
                    reader.onloadend = function() {
                        croppedImageInput.value = reader.result;
                        form.submit(); // now submit after setting base64 image
                    };
                    reader.readAsDataURL(blob);
                });
            }
        });
    </script>
@endsection
