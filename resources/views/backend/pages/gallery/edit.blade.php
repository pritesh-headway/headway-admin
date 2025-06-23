@extends('backend.layouts.master')

@section('title')
    Edit Gallery - Admin Panel
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />

    <style>
        #cropModal {
            margin-top: 15px;
            max-width: 100%;
        }

        #cropModal img {
            max-width: 100%;
            border: 1px solid #ccc;
            padding: 5px;
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

                        <form action="{{ route('admin.gallery.update', ['id' => $gallery->id]) }}?type={{ $type }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ $gallery->title }}" required>
                            </div>

                            <div class="form-group">
                                <label for="images">Gallery Image</label>
                                <input type="file" id="imagesInput" class="form-control" accept="image/*" />
                                <input type="hidden" name="cropped_image" id="croppedImageInput">

                                <br />
                                @php
                                    $folder = $type === 'ssu' ? 'ssu_gallery' : 'mmb_gallery';
                                @endphp
                                <img src="{{ asset($folder . '/' . $gallery->images) }}" alt="Current Image" width="100px"
                                    height="80px" />
                            </div>

                            <!-- Crop Modal (inline) -->
                            <div id="cropModal" style="display:none;">
                                <img id="imagePreview" />
                                <button type="button" id="cropBtn" class="btn btn-success mt-2">Crop</button>
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
        const cropModal = document.getElementById('cropModal');
        const croppedImageInput = document.getElementById('croppedImageInput');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    cropModal.style.display = 'block';

                    imagePreview.onload = function() {
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(imagePreview, {
                            aspectRatio: 233 / 117,
                            viewMode: 1,
                        });
                    };
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('cropBtn').addEventListener('click', function() {
            const canvas = cropper.getCroppedCanvas({
                width: 800,
                height: Math.round(800 * (117 / 233))
            });

            canvas.toBlob(function(blob) {
                const reader = new FileReader();
                reader.onloadend = function() {
                    croppedImageInput.value = reader.result;
                };
                reader.readAsDataURL(blob);
            });

            cropModal.style.display = 'none';
        });
    </script>
@endsection
