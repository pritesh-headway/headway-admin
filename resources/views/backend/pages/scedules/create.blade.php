@extends('backend.layouts.master')

@section('title')
Scedules - Admin Panel
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
                <h4 class="page-title pull-left">Scedule</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.banner.index') }}">Scedule</a></li>
                    <li><span>Scedule</span></li>
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
                    <h4 class="header-title">Create Scedule</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.scedules.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Batch</label>
                                <select class="form-control" id="batch" name="batch_id" required>
                                    <option value="">Select Batch</option>
                                    @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ old('batch_id')==$batch->id ? 'selected' : ''
                                        }}>
                                        {{ $batch->batch_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Modules</label>
                                <select class="form-control" id="modules" name="modules[]" required>
                                    <option value="">Select Modules</option>
                                    @foreach ($modules as $module)
                                    <option value="{{ $module->id }}" {{ in_array($module->id, old('modules', [])) ?
                                        'selected' : '' }}>
                                        {{ $module->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="email">Clients</label>
                                <select class="form-control select2" id="clients" name="clients[]" multiple required>
                                    <option value="">Select Clients</option>
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ in_array($client->id, old('clients', [])) ?
                                        'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.scedules.index') }}"
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
    })
</script>
@endsection
