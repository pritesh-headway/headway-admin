@extends('backend.layouts.master')

@section('title')
    {{ __('Settings - Admin Panel') }}
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">{{ __('Settings') }}</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li><span>{{ __('All Settings') }}</span></li>
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
                        <h4 class="header-title float-left">{{ __('Settings') }}</h4>
                        <p class="float-right mb-2">
                            {{-- @if (auth()->user()->can('settings.create')) --}}
                            <a class="btn btn-primary text-white" href="{{ route('admin.settings.create') }}">
                                {{ __('Create New Setting') }}
                            </a>
                            {{-- @endif --}}
                        </p>
                        <div class="clearfix"></div>

                        @include('backend.layouts.partials.messages')

                        <div class="data-tables">
                            <table id="dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>{{ __('Sl') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Value') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Group') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($settings as $key => $setting)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $setting->name }}</td>
                                            <td>{{ Str::limit($setting->value, 30) }}</td>
                                            <td>
                                                @if ($setting->status == '1')
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $setting->group }}</td>
                                            <td>{{ Str::limit($setting->desc, 40) }}</td>
                                            <td>
                                                {{-- @if (auth()->user()->can('settings.edit')) --}}
                                                <a class="btn btn-success btn-sm"
                                                    href="{{ route('admin.settings.edit', $setting->id) }}">Edit</a>
                                                {{-- @endif --}}

                                                @if (auth()->user()->can('settings.delete'))
                                                    <a class="btn btn-danger btn-sm text-white" href="javascript:void(0);"
                                                        onclick="showDeleteModal({{ $setting->id }})">
                                                        {{ __('Delete') }}
                                                    </a>

                                                    <form id="delete-form-{{ $setting->id }}"
                                                        action="{{ route('admin.settings.destroy', $setting->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Delete confirmation --}}
                        <script>
                            function showDeleteModal(id) {
                                if (confirm('Are you sure you want to delete this setting?')) {
                                    document.getElementById('delete-form-' + id).submit();
                                }
                            }
                        </script>
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
    </script>
@endsection
