@extends('backend.layouts.master')

@section('title')
    {{ __('Teams - Admin Panel') }}
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">{{ __('Teams') }}</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li><span>{{ __('All Teams') }}</span></li>
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
                        <h4 class="header-title float-left">{{ __('Teams') }}</h4>
                        <p class="float-right mb-2">
                            @if (auth()->user()->can('Ourteam.edit'))
                                <a class="btn btn-primary text-white" href="{{ route('admin.ourteam.create') }}">
                                    {{ __('Create New Team') }}
                                </a>
                            @endif
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('backend.layouts.partials.messages')
                            <table id="dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th width="1%">{{ __('Sl') }}</th>
                                        <th width="5%">{{ __('Name') }}</th>
                                        <th width="5%">{{ __('Position') }}</th>
                                        <th width="5%">{{ __('Image') }}</th>
                                        <th width="10%">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $admin->name }}</td>
                                            <td>{{ $admin->position }}</td>
                                            <td><img src="{{ asset('/teams/') }}/{{ $admin->image }}" width="100px"
                                                    height="80px"></td>
                                            <td>
                                                @if (auth()->user()->can('Ourteam.edit'))
                                                    <a class="btn btn-success btn-sm text-white"
                                                        href="{{ route('admin.ourteam.edit', $admin->id) }}">Edit</a>
                                                @endif

                                                @if (auth()->user()->can('Ourteam.delete'))
                                                    <a class="btn btn-danger btn-sm text-white" href="javascript:void(0);"
                                                        onclick="showDeleteModal({{ $admin->id }})">Delete</a>
                                                @endif

                                                {{-- Move Up --}}
                                                <form action="{{ route('admin.ourteam.moveup', $admin->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm"
                                                        title="Move Up">&#8679;</button>
                                                </form>

                                                {{-- Move Down --}}
                                                <form action="{{ route('admin.ourteam.movedown', $admin->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm"
                                                        title="Move Down">&#8681;</button>
                                                </form>

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
    </script>
@endsection
