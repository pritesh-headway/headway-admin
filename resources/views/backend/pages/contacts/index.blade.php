@extends('backend.layouts.master')

@section('title')
    {{ __('Contact Us - Admin Panel') }}
@endsection

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <style>
        .badge-status {
            padding: 0.4em 0.6em;
            font-size: 0.8rem;
        }

        .table td img {
            max-height: 80px;
        }
    </style>
@endsection

@section('admin-content')
    <!-- Page Title -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">{{ __('Contact Messages') }}</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li><span>{{ __('All Contacts') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 clearfix">
                @include('backend.layouts.partials.logout')
            </div>
        </div>
    </div>
    <!-- End Page Title -->

    <div class="main-content-inner">
        <div class="row">
            <!-- Table Start -->
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title">{{ __('Contact Submissions') }}</h5>
                        @include('backend.layouts.partials.messages')

                        <div class="data-tables">
                            <table id="contactTable" class="table table-bordered text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>City</th>
                                        <th>Message</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Submitted At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $i => $contact)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $contact->name }}</td>
                                            <td>{{ $contact->email ?? '-' }}</td>
                                            <td>{{ $contact->country_code }} {{ $contact->phone ?? '-' }}</td>
                                            <td>{{ $contact->city ?? '-' }}</td>
                                            <td>{{ Str::limit($contact->message, 50) }}</td>
                                            {{-- <td>
                                                @if ($contact->status == 1)
                                                    <span class="badge badge-success badge-status">Active</span>
                                                @else
                                                    <span class="badge badge-secondary badge-status">Inactive</span>
                                                @endif
                                            </td> --}}
                                            <td>{{ \Carbon\Carbon::parse($contact->created_at)->format('d M, Y h:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Table End -->
        </div>
    </div>
@endsection

@section('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contactTable').DataTable({
                responsive: true,

            });
        });
    </script>
@endsection
