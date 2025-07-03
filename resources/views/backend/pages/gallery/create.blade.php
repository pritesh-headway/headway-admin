@extends('backend.layouts.master')

@section('title')
    Gallery Create - Admin Panel
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    @if ($type !== 'gen')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
        <style>
            #imagePreview {
                max-width: 100%;
                margin-top: 15px;
                display: none;
                border: 1px solid #ccc;
                padding: 5px;
            }
        </style>
    @endif
@endsection

@section('admin-content')
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

    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Create New Gallery Item</h4>
                        @include('backend.layouts.partials.messages')

                        <form id="galleryForm" action="{{ route('admin.gallery.store') }}?type={{ $type }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf

                            @if ($type !== 'gen')
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            placeholder="Enter Title" required value="{{ old('title') }}">
                                    </div>
                                </div>
                            @endif

                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="imagesInput">
                                        Gallery Image{{ $type === 'gen' ? 's (Multiple Allowed)' : '' }}
                                    </label>
                                    <input type="file" id="imagesInput"
                                        name="{{ $type === 'gen' ? 'imagesInput[]' : 'imagesInput' }}" class="form-control"
                                        accept="image/*" {{ $type === 'gen' ? 'multiple required' : 'required' }} />

                                    @if ($type !== 'gen')
                                        <input type="hidden" name="cropped_image" id="croppedImageInput">
                                    @endif
                                </div>
                            </div>

                            @if ($type !== 'gen')
                                <div id="cropContainer"
                                    style="max-width: 600px; max-height: 350px; overflow: hidden; margin-top: 20px;">
                                    <img id="imagePreview" />
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                            <a href="{{ route('admin.gallery.index') }}"
                                class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('galleryForm').addEventListener('submit', function(e) {
            const input = document.getElementById('imagesInput');
            const files = input.files;
            const maxSizeMB = 10;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;

            for (let i = 0; i < files.length; i++) {
                if (files[i].size > maxSizeBytes) {
                    e.preventDefault();
                    alert(`File "${files[i].name}" exceeds the 10MB size limit.`);
                    return false;
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    @if ($type !== 'gen')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
        <script>
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
                            background: false
                        });
                    };
                };
                reader.readAsDataURL(file);
            });

            form.addEventListener('submit', function(e) {
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
                            form.submit();
                        };
                        reader.readAsDataURL(blob);
                    });
                }
            });
        </script>
    @endif
@endsection
