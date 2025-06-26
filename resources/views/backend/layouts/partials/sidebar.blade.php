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
                                class="{{ Route::is('admin.admins.index') || Route::is('admin.admins.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.admins.index') }}">All Admins</a>
                            </li>
                            @endif

                            @if ($usr->can('admin.create'))
                            <li class="{{ Route::is('admin.admins.create') ? 'active' : '' }}"><a
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
                                class="{{ Route::is('admin.banner.index') || Route::is('admin.banner.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.banner.index') }}">All Banners</a>
                            </li>
                            @endif

                            @if ($usr->can('banner.create'))
                            <li class="{{ Route::is('admin.banner.create') ? 'active' : '' }}"><a
                                    href="{{ route('admin.banner.create') }}">Create Banner</a></li>
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
                                class="{{ Route::is('admin.blogs.index') || Route::is('admin.blogs.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.blogs.index') }}">All Blogs</a>
                            </li>
                            @endif

                            @if ($usr->can('blogs.create'))
                            <li class="{{ Route::is('admin.blogs.create') ? 'active' : '' }}"><a
                                    href="{{ route('admin.blogs.create') }}">Create Blogs</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if (
                    $usr->can('customer.create') ||
                    $usr->can('customer.view') ||
                    $usr->can('customer.edit') ||
                    $usr->can('customer.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-users"></i><span>
                                Customers</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.customers.create') || Route::is('admin.customers.index') || Route::is('admin.customers.edit') || Route::is('admin.customers.show') ? 'in' : '' }}">

                            @if ($usr->can('customer.view'))
                            <li
                                class="{{ Route::is('admin.customers.index') || Route::is('admin.customers.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.customers.index') }}">All Customers</a>
                            </li>
                            @endif

                            @if ($usr->can('customer.create'))
                            <li class="{{ Route::is('admin.customers.create') ? 'active' : '' }}"><a
                                    href="{{ route('admin.customers.create') }}">Create
                                    Customer</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('scedule.create') || $usr->can('scedule.view') || $usr->can('scedule.edit') ||
                    $usr->can('scedule.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                Scedule Module
                            </span></a>
                        <ul
                            class="collapse {{ Route::is('admin.scedules.create') || Route::is('admin.scedules.index') || Route::is('admin.scedules.edit') || Route::is('admin.scedules.show') ? 'in' : '' }}">
                            @if ($usr->can('scedule.create'))
                            <li class="{{ Route::is('admin.scedules.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.scedules.create') }}">Scedule</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if (
                    $usr->can('members.create') ||
                    $usr->can('members.view') ||
                    $usr->can('members.edit') ||
                    $usr->can('members.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>Customers
                                Plan</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.members.create') || Route::is('admin.members.index') || Route::is('admin.members.edit') || Route::is('admin.members.show') ? 'in' : '' }}">

                            @if ($usr->can('members.view'))
                            <li
                                class="{{ Route::is('admin.members.index') || Route::is('admin.members.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.members.index') }}">All Members</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if (
                    $usr->can('cms.create') ||
                    $usr->can('cms.view') ||
                    $usr->can('cms.edit') ||
                    $usr->can('cms.delete') ||
                    $usr->can('modules.create') ||
                    $usr->can('modules.view') ||
                    $usr->can('modules.edit') ||
                    $usr->can('modules.delete') ||
                    $usr->can('servicemodule.create') ||
                    $usr->can('servicemodule.view') ||
                    $usr->can('servicemodule.edit') ||
                    $usr->can('servicemodule.delete') ||
                    $usr->can('ourcourses.create') ||
                    $usr->can('ourcourses.view') ||
                    $usr->can('ourcourses.edit') ||
                    $usr->can('ourcourses.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i
                                class="fa fa-file-text"></i><span>CMS</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.cms.create') || Route::is('admin.cms.index') || Route::is('admin.cms.edit') || Route::is('admin.cms.show') || Route::is('admin.modules.create') || Route::is('admin.modules.index') || Route::is('admin.modules.edit') || Route::is('admin.modules.show') || Route::is('admin.services.create') || Route::is('admin.services.index') || Route::is('admin.services.edit') || Route::is('admin.services.show') || Route::is('admin.batch.index') || Route::is('admin.batch.edit') || Route::is('admin.courses.create') || Route::is('admin.courses.index') || Route::is('admin.courses.edit') || Route::is('admin.courses.show') ? 'in' : '' }}">

                            @if ($usr->can('cms.view'))
                            <li
                                class="{{ Route::is('admin.cms.index') || Route::is('admin.cms.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.cms.index') }}">All CMS</a>
                            </li>
                            @endif

                            {{-- @if ($usr->can('servicemodule.view') || $usr->can('modules.view') ||
                            $usr->can('ourcourses.view'))
                            <li
                                class="{{ Route::is('admin.services.index')  || Route::is('admin.services.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.services.index') }}">All Service Module</a>
                            </li>

                            <li
                                class="{{ Route::is('admin.modules.index')  || Route::is('admin.modules.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.modules.index') }}">All Modules</a>
                            </li>

                            <li
                                class="{{ Route::is('admin.courses.index')  || Route::is('admin.courses.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.courses.index') }}">All Courses</a>
                            </li>


                            @endif
                            <li
                                class="{{ Route::is('admin.batch.index')  || Route::is('admin.batch.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.batch.index') }}">All Batch</a>
                            </li> --}}
                            {{-- @if ($usr->can('cms.create'))
                            <li class="{{ Route::is('admin.cms.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.cms.create') }}">Create CMS</a></li>
                            @endif --}}
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('servicemodule.view') || $usr->can('modules.view') || $usr->can('ourcourses.view'))
                    <li
                        class="{{ Route::is('admin.services.index') || Route::is('admin.services.edit') ? 'active' : '' }}">
                        <a href="{{ route('admin.services.index') }}"><i class="fa fa-user-secret"></i><span>All
                                Service</span>
                            Module</a>
                    </li>

                    <li
                        class="{{ Route::is('admin.modules.index') || Route::is('admin.modules.edit') ? 'active' : '' }}">
                        <a href="{{ route('admin.modules.index') }}"><i class="fa fa-user-secret"></i><span>All
                                Modules</span></a>
                    </li>

                    <li
                        class="{{ Route::is('admin.courses.index') || Route::is('admin.courses.edit') ? 'active' : '' }}">
                        <a href="{{ route('admin.courses.index') }}"><i class="fa fa-user-secret"></i><span>All
                                Courses</span></a>
                    </li>
                    @endif
                    <li class="{{ Route::is('admin.batch.index') || Route::is('admin.batch.edit') ? 'active' : '' }}">
                        <a href="{{ route('admin.batch.index') }}"><i class="fa fa-user-secret"></i><span>All
                                Batch</span></a>
                    </li>

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
                                class="{{ Route::is('admin.client.index') || Route::is('admin.client.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.client.index') }}">All Clients</a>
                            </li>
                            @endif

                            @if ($usr->can('client.create'))
                            <li class="{{ Route::is('admin.client.create') ? 'active' : '' }}"><a
                                    href="{{ route('admin.client.create') }}">Create Clients</a></li>
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
                                class="{{ Route::is('admin.event.index') || Route::is('admin.event.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.event.index') }}">All Events</a>
                            </li>
                            @endif

                            @if ($usr->can('event.create'))
                            <li class="{{ Route::is('admin.event.create') ? 'active' : '' }}"><a
                                    href="{{ route('admin.event.create') }}">Create Event</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if (
                    $usr->can('membership.create') ||
                    $usr->can('membership.view') ||
                    $usr->can('membership.edit') ||
                    $usr->can('membership.delete') ||
                    $usr->can('orderaddon.create') ||
                    $usr->can('orderaddon.view') ||
                    $usr->can('orderaddon.edit') ||
                    $usr->can('orderaddon.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-ticket"></i><span>Membership
                                Orders</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.membership.create') || Route::is('admin.membership.index') || Route::is('admin.membership.edit') || Route::is('admin.membership.show') || Route::is('admin.orderaddon.create') || Route::is('admin.orderaddon.index') || Route::is('admin.orderaddon.edit') || Route::is('admin.orderaddon.show') ? 'in' : '' }}">

                            @if ($usr->can('membership.view'))
                            <li
                                class="{{ Route::is('admin.membership.index') || Route::is('admin.membership.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.membership.index') }}">All Membership Order</a>
                            </li>
                            @endif
                            @if ($usr->can('orderaddon.view'))
                            <li
                                class="{{ Route::is('admin.orderaddon.index') || Route::is('admin.orderaddon.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.orderaddon.index') }}">All Add On Order</a>
                            </li>
                            @endif
                            {{-- @if ($usr->can('membership.create'))
                            <li class="{{ Route::is('admin.membership.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.membership.create') }}">Create
                                    Video</a></li>
                            @endif --}}
                        </ul>
                    </li>
                    @endif

                    @if (
                    $usr->can('plan.create') ||
                    $usr->can('plan.view') ||
                    $usr->can('plan.edit') ||
                    $usr->can('plan.delete') ||
                    $usr->can('addonservice.create') ||
                    $usr->can('addonservice.view') ||
                    $usr->can('addonservice.edit') ||
                    $usr->can('addonservice.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-paper-plane"></i><span>Plans &
                                AddOn Service</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.plan.create') || Route::is('admin.plan.index') || Route::is('admin.plan.edit') || Route::is('admin.plan.show') || Route::is('admin.addonservice.create') || Route::is('admin.addonservice.index') || Route::is('admin.addonservice.edit') || Route::is('admin.addonservice.show') ? 'in' : '' }}">

                            @if ($usr->can('plan.view'))
                            <li
                                class="{{ Route::is('admin.plan.index') || Route::is('admin.plan.edit') || Route::is('admin.plan.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.plan.index') }}">All Plans</a>
                            </li>
                            @endif

                            @if ($usr->can('addonservice.view'))
                            <li
                                class="{{ Route::is('admin.addonservice.index') || Route::is('admin.addonservice.edit') || Route::is('admin.addonservice.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.addonservice.index') }}">All Add On Service</a>
                            </li>
                            @endif

                            {{-- @if ($usr->can('plan.create'))
                            <li class="{{ Route::is('admin.plan.create')  ? 'active' : '' }}"><a
                                    href="{{ route('admin.plan.create') }}">Create Plan</a></li>
                            @endif --}}
                        </ul>
                    </li>
                    @endif

                    @if (
                    $usr->can('Ourteam.create') ||
                    $usr->can('Ourteam.view') ||
                    $usr->can('Ourteam.edit') ||
                    $usr->can('Ourteam.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-users"></i><span>Teams</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.ourteam.create') || Route::is('admin.ourteam.index') || Route::is('admin.ourteam.edit') || Route::is('admin.ourteam.show') ? 'in' : '' }}">

                            @if ($usr->can('Ourteam.view'))
                            <li
                                class="{{ Route::is('admin.ourteam.index') || Route::is('admin.ourteam.edit') || Route::is('admin.ourteam.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.ourteam.index') }}">All Teams</a>
                            </li>
                            @endif

                            @if ($usr->can('Ourteam.create'))
                            <li class="{{ Route::is('admin.ourteam.create') ? 'active' : '' }}"><a
                                    href="{{ route('admin.ourteam.create') }}">Create Teams</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if (
                    $usr->can('testimonial.create') ||
                    $usr->can('testimonial.view') ||
                    $usr->can('testimonial.edit') ||
                    $usr->can('testimonial.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i
                                class="fa fa-thumbs-up"></i><span>Testimonials</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.testimonial.create') || Route::is('admin.testimonial.index') || Route::is('admin.testimonial.edit') || Route::is('admin.testimonial.show') ? 'in' : '' }}">

                            @if ($usr->can('testimonial.view'))
                            <li
                                class="{{ Route::is('admin.testimonial.index') || Route::is('admin.testimonial.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.testimonial.index') }}">All Testimonials</a>
                            </li>
                            @endif

                            @if ($usr->can('testimonial.create'))
                            <li class="{{ Route::is('admin.testimonial.create') ? 'active' : '' }}"><a
                                    href="{{ route('admin.testimonial.create') }}">Create Testimonial</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if (
                    $usr->can('service.create') ||
                    $usr->can('service.view') ||
                    $usr->can('service.edit') ||
                    $usr->can('service.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-briefcase"></i><span>Our
                                Services</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.service.create') || Route::is('admin.service.index') || Route::is('admin.service.edit') || Route::is('admin.service.show') ? 'in' : '' }}">

                            @if ($usr->can('service.view'))
                            <li
                                class="{{ Route::is('admin.service.index') || Route::is('admin.service.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.service.index') }}">All Services</a>
                            </li>
                            @endif

                            @if ($usr->can('service.create'))
                            <li class="{{ Route::is('admin.service.create') ? 'active' : '' }}"><a
                                    href="{{ route('admin.service.create') }}">Create Service</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if (
                    $usr->can('videogallery.create') ||
                    $usr->can('videogallery.view') ||
                    $usr->can('videogallery.edit') ||
                    $usr->can('videogallery.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-play-circle"></i><span>Video
                                Gallery</span>
                        </a>
                        <ul
                            class="collapse {{ Route::is('admin.videogallery.create') || Route::is('admin.videogallery.index') || Route::is('admin.videogallery.edit') || Route::is('admin.videogallery.show') ? 'in' : '' }}">

                            @if ($usr->can('videogallery.view'))
                            <li
                                class="{{ Route::is('admin.videogallery.index') || Route::is('admin.videogallery.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.videogallery.index') }}">All Video Gallery</a>
                            </li>
                            @endif

                            @if ($usr->can('videogallery.create'))
                            <li class="{{ Route::is('admin.videogallery.create') ? 'active' : '' }}"><a
                                    href="{{ route('admin.videogallery.create') }}">Create
                                    Video</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    <li
                        class="{{ Route::is('admin.contact.index') || Route::is('admin.contact.edit') ? 'active' : '' }}">
                        <a href="{{ route('admin.contact.index') }}"> <i class="fa fa-book"></i><span></span>Contact
                            Requests</a>
                    </li>

                    <li
                        class="{{ Route::is('admin.settings.index') || Route::is('admin.settings.edit') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.index') }}"> <i class="fa fa-cog"></i><span></span>Settings
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->