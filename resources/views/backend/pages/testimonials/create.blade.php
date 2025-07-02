@extends('backend.layouts.master')

@section('title')
    Testimonial Create - Admin Panel
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
                    <h4 class="page-title pull-left">Client Review Create</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.testimonial.index') }}">All Client Reviews</a></li>
                        <li><span>Create Client Review</span></li>
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
                        <h4 class="header-title">Create New Client Review</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.testimonial.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="name">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Enter Title" required autofocus value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label for="type">Type</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="mmb" {{ old('type') == 'mmb' ? 'selected' : '' }}>MMB</option>
                                        <option value="idp" {{ old('type') == 'idp' ? 'selected' : '' }}>IDP</option>
                                        <option value="startup" {{ old('type') == 'startup' ? 'selected' : '' }}>Startup
                                        </option>
                                    </select>
                                </div>
                            </div>



                            <div class="form-row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label for="desc">Description</label>
                                    <textarea class="form-control" id="desc" name="desc" required></textarea>
                                    <small id="charLimit" class="form-text text-muted">Max: 300 characters</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label for="city">City/Location</label>
                                    <input type="text" class="form-control" id="city" name="city"
                                        placeholder="Enter City"value="{{ old('city') }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label for="shop_name">Shop Name</label>
                                    <input type="text" class="form-control" id="shop_name" name="shop_name"
                                        placeholder="Enter shop name"value="{{ old('shop_name') }}" required>
                                </div>
                            </div>

                            {{-- <div class="form-row">
                            <div class="form-group col-md-12 col-sm-12">
                                <label for="rating">Rating</label>
                                <input type="text" class="form-control" id="rating" name="rating"
                                    placeholder="Enter rating"value="{{ old('rating') }}">
                            </div>
                        </div> --}}

                            <div class="form-row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label for="rating">Rating: <span
                                            id="ratingValue">{{ old('rating', 3) }}</span></label>
                                    <input type="range" class="form-control-range" id="rating" name="rating"
                                        min="1" max="5" step="0.5" value="{{ old('rating', 3) }}"
                                        oninput="document.getElementById('ratingValue').innerText = this.value" required>
                                </div>
                            </div>





                            <div class="form-row">

                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="password">Profile Image</label>
                                    <input type="file" name="image" id="image" class="form-control" required />
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="username">Status</label>
                                    <select class="form-control " id="status" name="status" required>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive
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

            // Update on change
            $('#type').on('change', updateMaxLength);

            // Initial trigger (e.g. edit form or if old('type') is present)
            updateMaxLength();
        })
    </script>
@endsection
