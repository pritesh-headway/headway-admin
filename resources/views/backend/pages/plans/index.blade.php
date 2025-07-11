@extends ('backend.layouts.master')

@section('title')
{{ __('Plans - Admin Panel') }}
@endsection

@section('styles')
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
                <h4 class="page-title pull-left">{{ __('Plans') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('All Plans') }}</span></li>
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
                    <h4 class="header-title float-left">{{ __('Plans') }}</h4>
                    <p class="float-right mb-2">
                        @if (auth()->user()->can('plan.edit'))
                        {{-- < a class="btn btn-primary text-white" data-bs-toggle="modal"
                            data-bs-target="#redirectModal" href="#">
                            {{ __('Create New Plan') }}
                            </a> --}}
                            <button type="button" class="btn btn-primary" id="openModal" data-bs-toggle="modal"
                                data-toggle="modal" data-target="#redirectModal">
                                Create New Plan </button>
                            @endif
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">{{ __('Sl') }}</th>
                                    <th width="10%">{{ __('Plan Name') }}</th>
                                    <th width="10%">{{ __('Plan type') }}</th>
                                    {{-- <th width="10%">{{ __('Sort Description') }}</th> --}}
                                    <th width="15%">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                <tr>
                                    <td>{{ $loop->index + 1}}</td>
                                    <td>{{ $admin->plan_name}}</td>
                                    <td>{{ ($admin->plan_type) ? $admin->plan_type : '--'}}</td>
                                    {{-- <td>{{ $admin->sort_desc}}</td> --}}
                                    {{-- < td> <img src="{{ asset('/plans/') }}/{{ $admin->image }}" width="100px"
                                            height="80px"></td> --}}
                                        <td>
                                            @if (auth()->user()->can('plan.edit'))
                                            <a class="btn btn-success text-white"
                                                href="{{ route('admin.plan.edit', ['id' => $admin->id, 'type' => $admin->page_type]) }}">Edit</a>
                                            @endif

                                            @if (auth()->user()->can('plan.delete'))
                                            {{-- < a class="btn btn-danger text-white" href="javascript:void(0);"
                                                onclick="plan.preventDefault(); if(confirm('Are you sure you want to delete?')) {document.getElementById('delete-form-{{ $admin->id }}').submit(); }">
                                                {{ __('Delete') }}
                                                </a> --}}
                                                <a class="btn btn-danger text-white" href="javascript:void(0);"
                                                    onclick="showDeleteModal({{ $admin->id }})">
                                                    {{ __('Delete') }}
                                                </a>

                                                <form id="delete-form-{{ $admin->id }}"
                                                    action="{{ route('admin.plan.destroy', $admin->id) }}" method="POST"
                                                    style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
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

        <!-- Modal Create -->
        <div class="modal fade" id="redirectModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="redirectModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="position: absolute;margin-left: 25%;" id="redirectModalLabel">
                            Choose Plan Type
                        </h4>
                    </div>

                    <div class="modal-body">
                        <!-- full‑width buttons -->
                        <button class="btn btn-default btn-primary" style="width: 100%"
                            onclick="goTo('{{ route('admin.plan.create','type=mmb') }}')">
                            MMB
                        </button>
                        <button class="btn btn-default btn-primary" style="width: 100%"
                            onclick="goTo('{{ route('admin.plan.create','type=start-up') }}')">
                            Start up
                        </button>
                        <button class="btn btn-default btn-primary" style="width: 100%"
                            onclick="goTo('{{ route('admin.plan.create','type=idp') }}')">
                            IDP
                        </button>
                        <button class="btn btn-default btn-primary" style="width: 100%"
                            onclick="goTo('{{ route('admin.plan.create','type=revision-batch') }}')">
                            Revision Batch
                        </button>
                        <button class="btn btn-default btn-primary" style="width: 100%"
                            onclick="goTo('{{ route('admin.plan.create','type=stay-aware-live-renewal') }}')">
                            Stay aware & live renewal
                        </button>
                        {{-- <button class="btn btn-default btn-primary" style="width: 100%"
                            onclick="goTo('{{ route('admin.plan.create','type=meeting-with-sir') }}')">
                            Single meeting With Sir
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Create -->
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
    if ($('#dataTable').length) {
        $('#dataTable').DataTable({
            responsive: true
        });
    }
</script>
<script>
    function goTo(url) {
        $('#redirectModal').modal('hide');
        setTimeout(function () {
            // window.location.href = url;
            window.open(url, '_blank');
        }, 300);
    }
</script>
@endsection