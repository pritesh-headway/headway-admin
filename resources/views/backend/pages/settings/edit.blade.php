@extends('backend.layouts.master')

@section('title')
    {{ __('Edit Setting - Admin Panel') }}
@endsection

@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">{{ __('Edit Setting') }}</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li><a href="{{ route('admin.settings.index') }}">{{ __('Settings') }}</a></li>
                        <li><span>{{ __('Edit Setting') }}</span></li>
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
                        <h4 class="header-title">{{ __('Edit Setting') }}</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $setting->name) }}"
                                    class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="type">{{ __('Type') }} <span class="text-danger">*</span></label>
                                <select name="type" id="typeSelect" class="form-control" required>
                                    <option value="text" {{ $setting->type === 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="file" {{ $setting->type === 'file' ? 'selected' : '' }}>File</option>
                                </select>
                            </div>

                            <div class="form-group {{ $setting->type !== 'text' ? 'd-none' : '' }}" id="textValueField">
                                <label for="value">{{ __('Value') }} <span class="text-danger">*</span></label>
                                <textarea name="value" rows="3" class="form-control">{{ old('value', $setting->value) }}</textarea>
                            </div>

                            <div class="form-group {{ $setting->type !== 'file' ? 'd-none' : '' }}" id="fileValueField">
                                <label for="file">{{ __('Upload File') }} <span class="text-danger">*</span></label>
                                <input type="file" name="file" class="form-control-file">

                                @if ($setting->type === 'file' && $setting->value)
                                    <div class="mt-2">
                                        <strong>Current File:</strong>
                                        <a href="{{ asset($setting->value) }}" target="_blank" class="btn btn-sm btn-info ml-2">View</a>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="status">{{ __('Status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $setting->status == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $setting->status == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="group">{{ __('Group') }}</label>
                                <input type="text" name="group" value="{{ old('group', $setting->group) }}"
                                    class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="desc">{{ __('Description') }}</label>
                                <textarea name="desc" rows="3" class="form-control">{{ old('desc', $setting->desc) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Update Setting') }}</button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline JS -->
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
                }
            }

            typeSelect.addEventListener('change', toggleFields);
            toggleFields(); // Initialize on page load
        });
    </script>
@endsection
