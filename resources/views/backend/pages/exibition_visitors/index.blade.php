@extends('backend.layouts.master')

@section('title')
    Exhibition Visitors Management
@endsection

@section('admin-content')
    <div class="page-title-area">
        <h4 class="page-title">Exhibition Visitors</h4>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <div class="main-content-inner">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Visitor List</h5>
                    <form action="{{ route('admin.exibition_visitors.export') }}" method="POST" class="form-inline mb-3">
                        @csrf
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <label for="from" class="col-form-label">From</label>
                            </div>
                            <div class="col-auto">
                                <input type="date" name="from" id="from" class="form-control" required
                                    value="{{ request('from') }}">
                            </div>
                            <div class="col-auto">
                                <label for="to" class="col-form-label">To</label>
                            </div>
                            <div class="col-auto">
                                <input type="date" name="to" id="to" class="form-control" required
                                    value="{{ request('to') }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success">Export</button>
                            </div>
                        </div>
                    </form>


                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>SL</th>
                                <th>Jeweller's Name</th>
                                <th>Owner's Name</th>
                                <th>Mobile (1)</th>
                                <th>City</th>
                                <th>Enquired For</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($visitors as $index => $visitor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $visitor->jeweller_name }}</td>
                                    <td>{{ $visitor->owner_name }}</td>
                                    <td>{{ $visitor->mobile_1 }}</td>
                                    <td>{{ $visitor->city }}</td>
                                    <td>{{ $visitor->enquired_for }}</td>
                                    <td>
                                        <a href="{{ route('admin.exibition_visitors.view', $visitor->id) }}"
                                            class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('admin.exibition_visitors.edit', $visitor->id) }}"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="{{ route('admin.exibition_visitors.destroy', $visitor->id) }}"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No visitors found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
