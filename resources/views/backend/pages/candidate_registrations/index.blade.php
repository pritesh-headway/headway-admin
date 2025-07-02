@extends('backend.layouts.master')

@section('title')
    Candidate Registrations Management
@endsection

@section('admin-content')
    <div class="page-title-area">
        <h4 class="page-title">Candidate Registrations</h4>
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
                    <h5 class="card-title">Candidate List</h5>

                    <form action="{{ route('admin.candidate.export') }}" method="POST" class="form-inline mb-3">
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
                                <th>Name</th>
                                <th>Mobile 1</th>
                                <th>Email</th>
                                <th>Applying For</th>
                                <th>City</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($candidates as $index => $candidate)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $candidate->name }}</td>
                                    <td>{{ $candidate->mobile_1 }}</td>
                                    <td>{{ $candidate->email }}</td>
                                    <td>{{ $candidate->applying_for }}</td>
                                    <td>{{ $candidate->city }}</td>
                                    <td>
                                        <a href="{{ route('admin.candidate.view', $candidate->id) }}"
                                            class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('admin.candidate.edit', $candidate->id) }}"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="{{ route('admin.candidate.delete', $candidate->id) }}"
                                            onclick="return confirm('Are you sure?')"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No candidates found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
