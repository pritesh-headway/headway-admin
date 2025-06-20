@extends('backend.layouts.master')

@section('title')
{{ __('Our Products - Admin Panel') }}
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
                <h4 class="page-title pull-left">{{ __('Our Products') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('All Products') }}</span></li>
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
                    <h4 class="header-title float-left">{{ __('Our Products') }}</h4>
                    <p class="float-right mb-2">
                        {{-- @if (auth()->user()->can('ourproduct.create')) --}}
                        <a class="btn btn-primary text-white" href="{{ route('admin.our_products.create') }}">
                            {{ __('Create New Product') }}
                        </a>
                        {{-- @endif --}}
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="1%">{{ __('Sl') }}</th>
                                    <th width="5%">{{ __('Title') }}</th>
                                    <th width="5%">{{ __('Image') }}</th>
                                    <th width="5%">{{ __('Product Banner') }}</th>
                                    <th width="5%">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->title }}</td>
                                    <td>
                                        <img src="{{ asset('products/' . $product->photo) }}" width="100px" height="80px" alt="{{ $product->title }}">
                                    </td>
                                    <td>
                                        <img src="{{ asset('products/' . $product->product_banner) }}" width="100px" height="80px" alt="{{ $product->title }}">
                                    </td>
                                    <td>
                                        {{-- @if (auth()->user()->can('ourproduct.edit')) --}}
                                        <a class="btn btn-success text-white"
                                            href="{{ route('admin.our_products.edit', $product->id) }}">Edit</a>
                                        {{-- @endif --}}

                                        {{-- @if (auth()->user()->can('ourproduct.delete')) --}}
                                        <a class="btn btn-danger text-white" href="javascript:void(0);"
                                            onclick="showDeleteModal({{ $product->id }})">
                                            {{ __('Delete') }}
                                        </a>
                                        <form id="delete-form-{{ $product->id }}"
                                            action="{{ route('admin.our_products.destroy', $product->id) }}" method="POST"
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
        if (confirm('Are you sure you want to delete this product?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
