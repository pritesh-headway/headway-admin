@extends('backend.layouts.master')

@section('title')
    Gallery Management
@endsection

@section('admin-content')
    <div class="page-title-area">
        <h4 class="page-title">Gallery</h4>
    </div>

    <div class="main-content-inner">
        <ul class="nav nav-tabs mb-4" id="galleryTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="ssu-tab" data-toggle="tab" href="#ssu" role="tab">SSU</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="mmb-tab" data-toggle="tab" href="#mmb" role="tab">MMB</a>
            </li>
        </ul>

        <div class="tab-content" id="galleryTabContent">
            <!-- SSU Tab -->
            <div class="tab-pane fade show active" id="ssu" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h4>SSU Gallery</h4>
                        <a href="{{ route('admin.gallery.create', ['type' => 'ssu']) }}" class="btn btn-primary mb-3">Add
                            SSU Gallery</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ssuGalleries as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                                <img src="{{ asset('ssu_gallery/' . $item->images) }}" width="100">
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.gallery.edit', ['id' => $item->id, 'type' => 'ssu']) }}"
                                                class="btn btn-success btn-sm">Edit</a>
                                            <a href="{{ route('admin.gallery.delete', ['id' => $item->id, 'type' => 'ssu']) }}"
                                                onclick="return confirm('Are you sure?')"
                                                class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- MMB Tab -->
            <div class="tab-pane fade" id="mmb" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h4>MMB Gallery</h4>
                        <a href="{{ route('admin.gallery.create', ['type' => 'mmb']) }}" class="btn btn-primary mb-3">Add
                            MMB Gallery</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mmbGalleries as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            <img src="{{ asset('mmb_gallery/' . $item->images) }}" width="100">
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.gallery.edit', ['id' => $item->id, 'type' => 'mmb']) }}"
                                                class="btn btn-success btn-sm">Edit</a>
                                            <a href="{{ route('admin.gallery.delete', ['id' => $item->id, 'type' => 'mmb']) }}"
                                                onclick="return confirm('Are you sure?')"
                                                class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
