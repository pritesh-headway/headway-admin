@extends('backend.layouts.master')

@section('title')
{{ __('Startups - Admin Panel') }}
@endsection

@section('styles')
<!-- Start datatable css -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">{{ __('Startups') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('All Startups') }}</span></li>
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
                    <h4 class="header-title float-left">{{ __('Startups') }}</h4>
                    <p class="float-right mb-2">
                        {{-- @if (auth()->user()->can('aboutstartup.create')) --}}
                        <a class="btn btn-primary text-white" href="{{ route('admin.startups.create') }}">
                            {{ __('Create New Startup Info') }}
                        </a>
                        {{-- @endif --}}
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">{{ __('Sl') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($startups as $startup)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $startup->title }}</td>
                                    <td>{{ Str::limit($startup->description, 60) }}</td>
                                    <td>
                                        @if ($startup->status == 1)
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                        <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                        @endif
                                    <td>
                                        {{-- @if (auth()->user()->can('aboutstartup.edit')) --}}
                                        <a class="btn btn-success text-white"
                                            href="{{ route('admin.startups.edit', $startup->id) }}">Edit</a>
                                        {{-- @endif --}}

                                        {{-- @if (auth()->user()->can('aboutstartup.delete')) --}}
                                        <a class="btn btn-danger text-white" href="javascript:void(0);"
                                            onclick="showDeleteModal({{ $startup->id }})">
                                            {{ __('Delete') }}
                                        </a>

                                        <form id="delete-form-{{ $startup->id }}"
                                            action="{{ route('admin.startups.destroy', $startup->id) }}" method="POST"
                                            style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                        {{-- @endif --}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
</div>
@endsection

@section('scripts')
<!-- Start datatable js -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
    if ($('#dataTable').length) {
        $('#dataTable').DataTable({
            responsive: true
        });
    }

    function showDeleteModal(id) {
        if (confirm('Are you sure you want to delete?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
