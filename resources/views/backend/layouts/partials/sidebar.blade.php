<!-- sidebar menu area start -->
@php
$usr = Auth::guard('admin')->user();
@endphp
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}">
                {{-- <h2 class="text-white">HBS</h2> --}}
                <img src="{{ asset('backend/assets/images/Logo_headway.png') }}"
                    style="border-radius: 50%;width: 60px; ">
                <b style="font-size: 15px">&nbsp;&nbsp; HBS </b>
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">

                    @if ($usr->can('dashboard.view'))
                    <li class="active">
                    <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a
                            href="{{ route('admin.dashboard') }}"> <i class="fa fa-home"></i><span>Dashboard</span></a>
                    </li>
                    {{-- <ul class="collapse">
                        <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a
                                href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    </ul> --}}
                    </li>
                    @endif

                    {{-- @if ($usr->can('role.create') || $usr->can('role.view') || $usr->can('role.edit') ||
                    $usr->can('role.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                Roles & Permissions
                            </span></a>
                        <ul
                            class="collapse {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : '' }}">
                            @if ($usr->can('role.view'))
                            <li
                                class="{{ Route::is('admin.roles.index')  || Route::is('admin.roles.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.roles.index') }}">All Roles</a>
                            </li>
                            @endif
                            @if ($usr->can('role.create'))
                            <li class="{{ Route::is('admin.roles.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.roles.create') }}">Create Role</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif --}}


                    @if ($usr->can('admin.create') || $usr->can('admin.view') || $usr->can('admin.edit') ||
                    $usr->can('admin.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                                Admins
                            </span></a>
                        <ul
                            class="collapse {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : '' }}">

                            @if ($usr->can('admin.view'))
                            <li
                                class="{{ Route::is('admin.admins.index')  || Route::is('admin.admins.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.admins.index') }}">All Admins</a>
                            </li>
                            @endif

                            @if ($usr->can('admin.create'))
                            <li class="{{ Route::is('admin.admins.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.admins.create') }}">Create Admin</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('banner.create') || $usr->can('banner.view') || $usr->can('banner.edit') ||
                    $usr->can('banner.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i
                                class="fa fa-picture-o"></i><span>Banners</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.banner.create') || Route::is('admin.banner.index') || Route::is('admin.banner.edit') || Route::is('admin.banner.show') ? 'in' : '' }}">

                            @if ($usr->can('banner.view'))
                            <li
                                class="{{ Route::is('admin.banner.index')  || Route::is('admin.banner.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.banner.index') }}">All Banners</a>
                            </li>
                            @endif

                            @if ($usr->can('banner.create'))
                            <li class="{{ Route::is('admin.banner.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.banner.create') }}">Create Banner</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('cms.create') || $usr->can('cms.view') || $usr->can('cms.edit') ||
                    $usr->can('cms.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i
                                class="fa fa-file-text"></i><span>CMS</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.cms.create') || Route::is('admin.cms.index') || Route::is('admin.cms.edit') || Route::is('admin.cms.show') ? 'in' : '' }}">

                            @if ($usr->can('cms.view'))
                            <li
                                class="{{ Route::is('admin.cms.index')  || Route::is('admin.cms.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.cms.index') }}">All CMS</a>
                            </li>
                            @endif

                            @if ($usr->can('cms.create'))
                            <li class="{{ Route::is('admin.cms.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.cms.create') }}">Create CMS</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('blogs.create') || $usr->can('blogs.view') || $usr->can('blogs.edit') ||
                    $usr->can('blogs.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-rss"></i><span>Blogs</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.blogs.create') || Route::is('admin.blogs.index') || Route::is('admin.blogs.edit') || Route::is('admin.blogs.show') ? 'in' : '' }}">

                            @if ($usr->can('blogs.view'))
                            <li
                                class="{{ Route::is('admin.blogs.index')  || Route::is('admin.blogs.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.blogs.index') }}">All Blogs</a>
                            </li>
                            @endif

                            @if ($usr->can('blogs.create'))
                            <li class="{{ Route::is('admin.blogs.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.blogs.create') }}">Create Blogs</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('client.create') || $usr->can('client.view') || $usr->can('client.edit') ||
                    $usr->can('client.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i
                                class="fa fa-user-secret"></i><span>Clients</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.client.create') || Route::is('admin.client.index') || Route::is('admin.client.edit') || Route::is('admin.client.show') ? 'in' : '' }}">

                            @if ($usr->can('client.view'))
                            <li
                                class="{{ Route::is('admin.client.index')  || Route::is('admin.client.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.client.index') }}">All Clients</a>
                            </li>
                            @endif

                            @if ($usr->can('client.create'))
                            <li class="{{ Route::is('admin.client.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.client.create') }}">Create Clients</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if ($usr->can('Ourteam.create') || $usr->can('Ourteam.view') || $usr->can('Ourteam.edit') ||
                    $usr->can('Ourteam.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-users"></i><span>Teams</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.ourteam.create') || Route::is('admin.ourteam.index') || Route::is('admin.ourteam.edit') || Route::is('admin.ourteam.show') ? 'in' : '' }}">

                            @if ($usr->can('Ourteam.view'))
                            <li
                                class="{{ Route::is('admin.ourteam.index')  || Route::is('admin.ourteam.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.ourteam.index') }}">All Teams</a>
                            </li>
                            @endif

                            @if ($usr->can('Ourteam.create'))
                            <li class="{{ Route::is('admin.ourteam.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.ourteam.create') }}">Create Teams</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('event.create') || $usr->can('event.view') || $usr->can('event.edit') ||
                    $usr->can('event.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i
                                class="fa fa-calendar"></i><span>Events</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.event.create') || Route::is('admin.event.index') || Route::is('admin.event.edit') || Route::is('admin.event.show') ? 'in' : '' }}">

                            @if ($usr->can('event.view'))
                            <li
                                class="{{ Route::is('admin.event.index')  || Route::is('admin.event.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.event.index') }}">All Events</a>
                            </li>
                            @endif

                            @if ($usr->can('event.create'))
                            <li class="{{ Route::is('admin.event.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.event.create') }}">Create Event</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('plan.create') || $usr->can('plan.view') || $usr->can('plan.edit') ||
                    $usr->can('plan.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-paper-plane"></i><span>Service
                                Plans</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.plan.create') || Route::is('admin.plan.index') || Route::is('admin.plan.edit') || Route::is('admin.plan.show') ? 'in' : '' }}">

                            @if ($usr->can('plan.view'))
                            <li
                                class="{{ Route::is('admin.plan.index')  || Route::is('admin.plan.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.plan.index') }}">All Service
                                    Plans</a>
                            </li>
                            @endif

                            @if ($usr->can('plan.create'))
                            <li class="{{ Route::is('admin.plan.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.plan.create') }}">Create Plans</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('testimonial.create') || $usr->can('testimonial.view') ||
                    $usr->can('testimonial.edit') ||
                    $usr->can('testimonial.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i
                                class="fa fa-paper-plane"></i><span>Testimonials</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.testimonial.create') || Route::is('admin.testimonial.index') || Route::is('admin.testimonial.edit') || Route::is('admin.testimonial.show') ? 'in' : '' }}">

                            @if ($usr->can('testimonial.view'))
                            <li
                                class="{{ Route::is('admin.testimonial.index')  || Route::is('admin.testimonial.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.testimonial.index') }}">All Testimonials</a>
                            </li>
                            @endif

                            @if ($usr->can('testimonial.create'))
                            <li class="{{ Route::is('admin.testimonial.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.testimonial.create') }}">Create Testimonial</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->