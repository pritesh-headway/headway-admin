@extends('backend.layouts.master')

@section('title')
    {{ __('Create Setting - Admin Panel') }}
@endsection

@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">{{ __('Create Setting') }}</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li><a href="{{ route('admin.settings.index') }}">{{ __('Settings') }}</a></li>
                        <li><span>{{ __('Create') }}</span></li>
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
            <div class="col-lg-8 offset-lg-2 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{ __('Create New Setting') }}</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="type">{{ __('Type') }} <span class="text-danger">*</span></label>
                                <select name="type" id="typeSelect" class="form-control" required>
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="file" {{ old('type') == 'file' ? 'selected' : '' }}>File</option>
                                </select>
                            </div>

                            <div class="form-group d-none" id="textValueField">
                                <label for="value">{{ __('Value') }} <span class="text-danger">*</span></label>
                                <textarea name="value" rows="3" class="form-control">{{ old('value') }}</textarea>
                            </div>

                            <div class="form-group d-none" id="fileValueField">
                                <label for="file">{{ __('Upload File') }} <span class="text-danger">*</span></label>
                                <input type="file" name="file" class="form-control-file">
                            </div>

                            <div class="form-group">
                                <label for="status">{{ __('Status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="group">{{ __('Group') }}</label>
                                <input type="text" name="group" class="form-control" value="{{ old('group') }}">
                            </div>

                            <div class="form-group">
                                <label for="desc">{{ __('Description') }}</label>
                                <textarea name="desc" rows="3" class="form-control">{{ old('desc') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Create Setting') }}</button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline script for showing the correct input based on type -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('typeSelect');
            const textField = document.getElementById('textValueField');
            const fileField = document.getElementById('fileValueField');

            function toggleFields() {
                const selected = typeSelect.value;
                if (selected === 'text') {
                    textField.classList.remove('d-none');
                    fileField.classList.add('d-none');
                } else if (selected === 'file') {
                    fileField.classList.remove('d-none');
                    textField.classList.add('d-none');
                } else {
                    textField.classList.add('d-none');
                    fileField.classList.add('d-none');
                }
            }

            typeSelect.addEventListener('change', toggleFields);
            toggleFields(); // trigger on page load
        });
    </script>
@endsection
