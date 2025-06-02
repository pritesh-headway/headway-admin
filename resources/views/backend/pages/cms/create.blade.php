@extends('backend.layouts.master')

@section('title')
CMS Create - Admin Panel
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
                <h4 class="page-title pull-left">CMS Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.cms.index') }}">All CMS</a></li>
                    <li><span>Create CMS</span></li>
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
                    <h4 class="header-title">Create New CMS</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.cms.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="name">Title</label>
                                <input type="text" class="form-control" id="page_name" name="page_name"
                                    placeholder="Enter Title" required autofocus value="{{ old('page_name') }}">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="name">Plan Name</label>
                                    <select class="form-control " id="plan_id" name="plan_id">
                                        <option value="">Select Plans</option>
                                        @foreach ($plan as $pl)
                                        <option value="{{ $pl->id }}" {{ old('plan_id')==$pl->id ? 'selected' : '' }}>{{
                                            $pl->plan_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12 col-sm-12">
                                <label for="password">Description</label><br />
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                        </div>

                        {{-- <div class="form-row">

                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Status</label>
                                <select class="form-control " id="status" name="status" required>
                                    <option value="1" {{ old('status')=='1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status')=='0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div> --}}

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.cms.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
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
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('description');
    setTimeout(() => {
        $('.cke_notification_warning').hide();
    }, 1000);
</script>
<script>
    CKEDITOR.replace('description');

    $('#plan_id').on('change', function () {
        let pageId = $(this).val();
        let url = "{!! route('admin.admin.cms.get-cms-content', ':id') !!}".replace(':id',pageId);
        if (pageId) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    if (response.success) {
                    CKEDITOR.instances.description.setData(response.data.content);
                    } else {
                    alert('Content not found');
                    CKEDITOR.instances.description.setData('');
                    }
                },
                error: function () {
                    alert('Error fetching data.');
                }
            });
        }
    });
</script>
@endsection