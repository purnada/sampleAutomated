<aside class="app-sidebar" id="sidebar">
    <div class="main-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
            <img src="{{ asset('assets/img/brand-logos/desktop-logo.png') }}" alt="logo"
                class="main-logo desktop-logo" />
            <img src="{{ asset('assets/img/brand-logos/toggle-logo.png') }}" alt="logo"
                class="main-logo toggle-logo" />
            <img src="{{ asset('assets/img/brand-logos/desktop-dark.png') }}" alt="logo"
                class="main-logo desktop-dark" />
            <img src="{{ asset('assets/img/brand-logos/toggle-dark.png') }}" alt="logo"
                class="main-logo toggle-dark" />
        </a>
    </div>
    <div class="main-sidebar" id="sidebar-scroll">
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">

                <li class="slide @if (request()->is('admin')) active @endif">
                    <a href="{{ route('admin.dashboard') }}"
                        class="side-menu__item @if (request()->is('admin')) active @endif">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                            <path fill="#9399a1"
                                d="M18 22H6a3.003 3.003 0 0 1-3-3v-8.75a3 3 0 0 1 1.023-2.257l6.001-5.25a3.012 3.012 0 0 1 3.952 0l6 5.25A3 3 0 0 1 21 10.25V19a3.003 3.003 0 0 1-3 3Z" />
                            <path fill="#4b5563" d="M16 22H8v-7a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3Z" />
                        </svg>
                        <span class="side-menu__label">Dashboards</span>
                    </a>

                </li>
                @can('view error log', 'view role', 'view user', 'view permission', 'view language', 'view setting')
                    <li class="slide has-sub @if (request()->is('log-viewer') ||
                            request()->is('admin/users') ||
                            request()->is('admin/permissions') ||
                            request()->is('admin/languages') ||
                            request()->is('admin/settings') ||
                            request()->is('admin/roles')) active open @endif">
                        <a href="javascript:void(0);"
                            class="side-menu__item @if (request()->is('log-viewer') ||
                                    request()->is('admin/users') ||
                                    request()->is('admin/permissions') ||
                                    request()->is('admin/languages') ||
                                    request()->is('admin/settings') ||
                                    request()->is('admin/roles')) active @endif">
                            <i class="ti ti-settings icon"></i>
                            <span class="side-menu__label">Settings</span> <i
                                class="ri ri-arrow-right-s-line side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1 @if (request()->is('log-viewer') ||
                                request()->is('admin/users') ||
                                request()->is('admin/permissions') ||
                                request()->is('admin/languages') ||
                                request()->is('admin/settings') ||
                                request()->is('admin/roles')) active @endif">
                            @can('view error log')
                                <li class="slide @if (request()->is('log-viewer')) active @endif">
                                    <a class="side-menu__item @if (request()->is('log-viewer')) active @endif"
                                        href="{{ url('log-viewer') }}">Logs</a>
                                </li>
                            @endcan
                            @can('view setting')
                                <li class="slide @if (request()->is('admin/settings')) active open @endif">
                                    <a class="side-menu__item @if (request()->is('admin/settings')) active @endif"
                                        href="{{ route('admin.settings.index') }}">Setting</a>
                                </li>
                            @endcan
                            @can('view role')
                                <li class="slide @if (request()->is('admin/roles')) active open @endif">
                                    <a class="side-menu__item @if (request()->is('admin/roles')) active @endif"
                                        href="{{ route('admin.roles.index') }}">Roles</a>
                                </li>
                            @endcan
                            @can('view permission')
                                <li class="slide @if (request()->is('admin/permissions')) active open @endif">
                                    <a class="side-menu__item @if (request()->is('admin/permissions')) active @endif"
                                        href="{{ route('admin.permissions.index') }}">Permission</a>
                                </li>
                            @endcan
                            @can('view user')
                                <li class="slide @if (request()->is('admin/users')) active open @endif">
                                    <a class="side-menu__item @if (request()->is('admin/users')) active @endif"
                                        href="{{ route('admin.users.index') }}">Users</a>
                                </li>
                            @endcan
                            @can('view language')
                                <li class="slide @if (request()->is('admin/languages')) active open @endif">
                                    <a class="side-menu__item @if (request()->is('admin/languages')) active @endif"
                                        href="{{ route('admin.languages.index') }}">Languages</a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan






  @can('view sector')<li class="slide @if(request()->is('sectors')) active @endif">
 <a href="{{ route('admin.sectors.index') }}" class="side-menu__item @if(request()->is('admin/sectors*')) active @endif">
 <i class="ti ti-menu icon"></i><span class="side-menu__label">Department</span>
</a>
 </li>@endcan


  @can('view appointment')<li class="slide @if(request()->is('appointments')) active @endif">
 <a href="{{ route('admin.appointments.index') }}" class="side-menu__item @if(request()->is('admin/appointments*')) active @endif">
 <i class="ti ti-calendar icon"></i><span class="side-menu__label">Appointments</span>
</a>
 </li>@endcan
 @can('view doctor')<li class="slide @if(request()->is('doctors')) active @endif">
    <a href="{{ route('admin.doctors.index') }}" class="side-menu__item @if(request()->is('admin/doctors*')) active @endif">
    <i class="ti ti-users icon"></i><span class="side-menu__label">Doctors</span>
   </a>
    </li>@endcan


  @can('view province')<li class="slide @if(request()->is('provinces')) active @endif">
 <a href="{{ route('admin.provinces.index') }}" class="side-menu__item @if(request()->is('admin/provinces*')) active @endif">
 <i class="ti ti-map icon"></i><span class="side-menu__label">Provinces</span>
</a>
 </li>@endcan


  @can('view district')<li class="slide @if(request()->is('districts')) active @endif">
 <a href="{{ route('admin.districts.index') }}" class="side-menu__item @if(request()->is('admin/districts*')) active @endif">
 <i class="ti ti-map icon icon"></i><span class="side-menu__label">Districts</span>
</a>
 </li>@endcan


  @can('view municipality')<li class="slide @if(request()->is('municipalities')) active @endif">
 <a href="{{ route('admin.municipalities.index') }}" class="side-menu__item @if(request()->is('admin/municipalities*')) active @endif">
 <i class="ti ti-map icon icon"></i><span class="side-menu__label">Municipalities</span>
</a>
 </li>@endcan


  @can('view leave')<li class="slide @if(request()->is('leaves')) active @endif">
 <a href="{{ route('admin.leaves.index') }}" class="side-menu__item @if(request()->is('admin/leaves*')) active @endif"> 
 <i class="ti ti-settings icon"></i><span class="side-menu__label">Leaves</span> 
</a> 
 </li>@endcan
</ul><div class="slide-right" id="slide-right">

                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>

        </nav>
    </div>
</aside>
