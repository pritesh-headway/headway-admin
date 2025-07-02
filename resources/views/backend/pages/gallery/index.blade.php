@extends('backend.layouts.master')

@section('title')
    Gallery Management
@endsection

@section('admin-content')
    @php
        $activeTab = 'ssu'; // default
        if (request()->has('mmb')) {
            $activeTab = 'mmb';
        } elseif (request()->has('oss')) {
            $activeTab = 'oss';
        } elseif (request()->has('gen')) {
            $activeTab = 'gen';
        }
    @endphp

    <div class="page-title-area">
        <h4 class="page-title">Gallery</h4>
    </div>

    <div class="main-content-inner">
        <ul class="nav nav-tabs mb-4" id="galleryTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'ssu' ? 'active' : '' }}" id="ssu-tab" data-toggle="tab" href="#ssu"
                    role="tab">SSU</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'mmb' ? 'active' : '' }}" id="mmb-tab" data-toggle="tab"
                    href="#mmb" role="tab">MMB</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'oss' ? 'active' : '' }}" id="oss-tab" data-toggle="tab"
                    href="#oss" role="tab">OSS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'gen' ? 'active' : '' }}" id="gen-tab" data-toggle="tab"
                    href="#gen" role="tab">GEN</a>
            </li>
        </ul>

        <div class="tab-content" id="galleryTabContent">
            <!-- SSU Tab -->
            <div class="tab-pane fade {{ $activeTab == 'ssu' ? 'show active' : '' }}" id="ssu" role="tabpanel">
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
                                        <td>{{ $index + $ssuGalleries->firstItem() }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td><img src="{{ asset('ssu_gallery/' . $item->images) }}" width="100"></td>
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
                        <div class="mt-3">
                            {{ $ssuGalleries->appends(['ssu' => request('ssu')])->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- MMB Tab -->
            <div class="tab-pane fade {{ $activeTab == 'mmb' ? 'show active' : '' }}" id="mmb" role="tabpanel">
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
                                        <td>{{ $index + $mmbGalleries->firstItem() }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td><img src="{{ asset('mmb_gallery/' . $item->images) }}" width="100"></td>
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
                        <div class="mt-3">
                            {{ $mmbGalleries->appends(['mmb' => request('mmb')])->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- OSS Tab -->
            <div class="tab-pane fade {{ $activeTab == 'oss' ? 'show active' : '' }}" id="oss" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h4>OSS Gallery</h4>
                        <a href="{{ route('admin.gallery.create', ['type' => 'oss']) }}" class="btn btn-primary mb-3">Add
                            OSS Gallery</a>
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
                                @foreach ($ossGalleries as $index => $item)
                                    <tr>
                                        <td>{{ $index + $ossGalleries->firstItem() }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td><img src="{{ asset('oss_gallery/' . $item->images) }}" width="100"></td>
                                        <td>
                                            <a href="{{ route('admin.gallery.edit', ['id' => $item->id, 'type' => 'oss']) }}"
                                                class="btn btn-success btn-sm">Edit</a>
                                            <a href="{{ route('admin.gallery.delete', ['id' => $item->id, 'type' => 'oss']) }}"
                                                onclick="return confirm('Are you sure?')"
                                                class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $ossGalleries->appends(['oss' => request('oss')])->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- GEN Tab -->
            <div class="tab-pane fade {{ $activeTab == 'gen' ? 'show active' : '' }}" id="gen" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h4>GEN Gallery</h4>
                        <a href="{{ route('admin.gallery.create', ['type' => 'gen']) }}" class="btn btn-primary mb-3">Add
                            Gen Gallery</a>
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
                                @foreach ($genGalleries as $index => $item)
                                    <tr>
                                        <td>{{ $index + $genGalleries->firstItem() }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td><img src="{{ asset('gen_gallery/' . $item->images) }}" width="100"></td>
                                        <td>
                                            <a href="{{ route('admin.gallery.edit', ['id' => $item->id, 'type' => 'gen']) }}"
                                                class="btn btn-success btn-sm">Edit</a>
                                            <a href="{{ route('admin.gallery.delete', ['id' => $item->id, 'type' => 'gen']) }}"
                                                onclick="return confirm('Are you sure?')"
                                                class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $genGalleries->appends(['gen' => request('gen')])->links('pagination::simple-bootstrap-4') }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
