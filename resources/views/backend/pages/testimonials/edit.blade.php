@extends('backend.layouts.master')

@section('title')
    Testimonial Edit - Admin Panel
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
                    <h4 class="page-title pull-left">Client Review Edit</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.testimonial.index') }}">All Client Reviews</a></li>
                        <li><span>Edit Client Review - {{ $admin->title }}</span></li>
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
                        <h4 class="header-title">Edit Client Review - {{ $admin->title }}</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.testimonial.update', $admin->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="name">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Enter Title" value="{{ $admin->title }}" required autofocus>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label for="type">Type</label>
                                    @php $selectedType = old('type') ?? $admin->type; @endphp
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="" disabled {{ !$selectedType ? 'selected' : '' }}>Select Type
                                        </option>
                                        <option value="mmb" {{ $selectedType == 'mmb' ? 'selected' : '' }}>MMB</option>
                                        <option value="idp" {{ $selectedType == 'idp' ? 'selected' : '' }}>IDP</option>
                                        <option value="startup" {{ $selectedType == 'startup' ? 'selected' : '' }}>Startup
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label for="desc">Description</label>
                                    <textarea class="form-control" id="desc" name="desc" required>{{ old('desc', $admin->description) }}</textarea>
                                    <small id="charLimit" class="form-text text-muted">Max: 300 characters</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <label for="city">City/Location</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    placeholder="Enter City" value="{{ $admin->city }}" required>

                            </div>

                            <div class="form-row">
                                <label for="shop_name">Shop Name</label>
                                <input type="text" class="form-control" id="shop_name" name="shop_name"
                                    placeholder="Enter Shop Name" value="{{ $admin->shop_name }}" required>

                            </div>

                            {{-- <div class="form-row">
                                <label for="rating">Rating</label>
                                <input type="text" class="form-control" id="rating" name="rating"
                                    placeholder="Enter rating" value="{{ $admin->rating }}">

                        </div> --}}

                            <div class="form-group">
                                <label for="rating">Rating: <span id="ratingValue">{{ $admin->rating }}</span></label>
                                <input type="range" class="form-control-range" id="rating" name="rating"
                                    min="1" max="5" step="0.5" value="{{ $admin->rating }}"
                                    oninput="document.getElementById('ratingValue').innerText = this.value" required>
                            </div>





                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="password">Profile Image</label>
                                    <input type="file" name="image" id="image" class="form-control" />
                                    <br />
                                    <img src="{{ asset('testimonials/' . $admin->image) }}" alt="Profile Image"
                                        width="100px" height="80px" />
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="username">Status</label>
                                    <select class="form-control " id="status" name="status" required>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}
                                            {{ $admin->status == '1' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}
                                            {{ $admin->status == '0' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                            <a href="{{ route('admin.testimonial.index') }}"
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

            const typeLimits = {
                idp: 300,
                startup: 300,
                mmb: 300
            };

            function updateMaxLength() {
                const selectedType = $('#type').val();
                const max = typeLimits[selectedType] || 300;
                $('#desc').attr('maxlength', max);
                $('#charLimit').text(`Max: ${max} characters`);
            }

            // Bind change event
            $('#type').on('change', updateMaxLength);

            // Run on page load in case editing
            updateMaxLength();
        })
    </script>
@endsection
