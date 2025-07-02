@extends('backend.layouts.master')

@section('title')
    View Visitor Details
@endsection

@section('admin-content')
    <div class="page-title-area">
        <h4 class="page-title">Visitor Details</h4>
    </div>

    <div class="main-content-inner">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('admin.exibition_visitors.index') }}" class="btn btn-sm btn-secondary mb-3">
                    ← Back to List
                </a>

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">Event Venue</th>
                            <td>{{ $visitor->event_venue ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Jeweller's Name</th>
                            <td>{{ $visitor->jeweller_name }}</td>
                        </tr>
                        <tr>
                            <th>Owner's Name</th>
                            <td>{{ $visitor->owner_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $visitor->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Mobile Number (1)</th>
                            <td>{{ $visitor->mobile_1 }}</td>
                        </tr>
                        <tr>
                            <th>Mobile Number (2)</th>
                            <td>{{ $visitor->mobile_2 ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $visitor->address }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $visitor->city }}</td>
                        </tr>
                        <tr>
                            <th>Enquired For</th>
                            <td>{{ $visitor->enquired_for }}</td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td>{{ $visitor->remarks ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Submitted At</th>
                            <td>{{ $visitor->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
