@php
$usr = Auth::guard('admin')->user();
@endphp
<div class="user-profile pull-right">
    {{-- <img class="avatar user-thumb" src="{{ asset('backend/assets/images/author/avatar.png') }}" alt="avatar"> --}}
    <h4 class="user-name dropdown-toggle" data-toggle="dropdown">
        {{ Auth::guard('admin')->user()->name }}
        <i class="fa fa-angle-down"></i>
    </h4>
    <div class="dropdown-menu">
        @if ($usr->can('role.create') || $usr->can('role.view') || $usr->can('role.edit') ||
        $usr->can('role.delete'))
        <li class="">
            @if ($usr->can('role.view'))
            <a class="dropdown-item" href="{{ route('admin.roles.index') }}" aria-expanded="true">
                Roles & Permissions
            </a>
            @endif
        </li>
        @endif
        <a class="dropdown-item" href="{{ route('admin.logout.submit') }}" onclick="event.preventDefault();
                      document.getElementById('admin-logout-form').submit();">Log Out</a>
    </div>

    <form id="admin-logout-form" action="{{ route('admin.logout.submit') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
