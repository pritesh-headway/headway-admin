@extends('backend.layouts.master')

@section('title')
{{ __('Customers Plan Approved - Admin Panel') }}
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
                <h4 class="page-title pull-left">{{ __('Customers Plan Approved') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('All Customers Plan Approved') }}</span></li>
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
                    <h4 class="header-title float-left">{{ __('Customers Plan Approved') }}</h4>
                    <p class="float-right mb-2">
                        @if (auth()->user()->can('membership.edit'))
                        {{-- <a class="btn btn-primary text-white" href="{{ route('admin.membership.create') }}">
                            {{ __('Create New Membership') }}
                        </a> --}}
                        @endif
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="1%">{{ __('Sl') }}</th>
                                    <th width="10%">{{ __('Name') }}</th>
                                    <th width="30%">{{ __('Mobile No') }}</th>
                                    <th width="30%">{{ __('Page Type') }}</th>
                                    <th width="30%">{{ __('Plan Type') }}</th>
                                    <th width="15%">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $admin->full_name }}</td>
                                    <td>{{ $admin->mobile_no }}</td>
                                    <td>{{ $admin['plans']->page_type }}</td>
                                    <td>{{ $admin['plans']->plan_type }}</td>
                                    {{-- <td>
                                        @if ($admin->membership_status=='Declined')
                                        <div style="background-color: orange;padding: 5px;">{{ $admin->membership_status
                                            }} </div>
                                        @elseif ($admin->membership_status=='Approved')
                                        <div style="background-color: green;padding: 5px;">{{ $admin->membership_status
                                            }} </div>
                                        @elseif ($admin->membership_status=='Pending')
                                        <div style="background-color: red;padding: 5px;">{{ $admin->membership_status }}
                                        </div>
                                        @endif
                                    </td> --}}
                                    <td>
                                        @if (auth()->user()->can('members.edit'))
                                        <a class="btn btn-success text-white"
                                            href="{{ route('admin.members.edit', $admin->id) }}">Detail</a>
                                        @endif
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
                responsive: false
            });
        }
</script>
@endsection