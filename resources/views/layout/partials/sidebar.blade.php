@if (! Route::is(['pos','update-credentials','pos-2','pos-3','pos-4','pos-5']))
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo active">
        <a href="{{url('index')}}" class="logo logo-normal">
            <img src="{{URL::asset('build/img/logo-whit.png')}}" alt="Img">
        </a>
        <a href="{{url('index')}}" class="logo logo-white">
            <img src="{{URL::asset('build/img/logo.png')}}" alt="Img">
        </a>
        <a href="{{url('index')}}" class="logo-small">
            <img src="{{URL::asset('#')}}" alt="Img">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->
    <div class="modern-profile p-3 pb-0">
        <div class="text-center rounded bg-light p-3 mb-4 user-profile">
            <div class="avatar avatar-lg online mb-3">
                <img src="{{URL::asset('build/img/logo.png')}}" alt="Img" class="img-fluid rounded-circle">
            </div>
            <h6 class="fs-12 fw-normal mb-1">{{ auth()->user()->name }}</h6>
            <p class="fs-10 mb-0">{{ auth()->user()->role }}</p>
        </div>
        <div class="sidebar-nav mb-3">
            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified bg-transparent" role="tablist">
                <li class="nav-item"><a class="nav-link active border-0" href="#">Menu</a></li>
                <li class="nav-item"><a class="nav-link border-0" href="#">Chats</a></li>
                <li class="nav-item"><a class="nav-link border-0" href="#">Inbox</a></li>
            </ul>
        </div>
    </div>
    <div class="sidebar-header p-3 pb-0 pt-2">
        <div class="text-center rounded bg-light p-2 mb-4 sidebar-profile d-flex align-items-center">
            <div class="avatar avatar-md onlin">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('build/img/users/default.png') }}" alt="Img" class="img-fluid rounded-circle">
            </div>
            <div class="text-start sidebar-profile-info ms-2">
                <h6 class="fs-12 fw-normal mb-1">{{ auth()->user()->name }}</h6>
                <p class="fs-10">{{ auth()->user()->role }}</p>
            </div>
        </div>

    </div>
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">FLEET MANAGEMENT</h6>
                    <ul>
                        <li class="submenu-open">
                            <ul>
                                @hasanyrole('admin|developer|manager|director|supervisor')
                                @can('view dashboard')
                                <li class="{{ Request::is('index', 'dashboard') ? 'active' : '' }}">
                                    <a href="{{ url('index') }}">
                                        <i class="fas fa-user-shield me-2"></i><span>Dashboard</span>
                                    </a>
                                </li>
                                @endcan
                                @endhasanyrole
                               
                            </ul>
                        </li>
                      

                     

                       
      
                    </ul>
                     
                </li>
                   <li class="submenu-open">
                        <ul>
                            @can('view driver')                        
                            <li class="submenu">
                                <a href="javascript:void(0);" class="{{ Request::is('drivers','driver-history') ? 'active' : '' }}"><i class="ti ti-steering-wheel fs-16 me-2"></i><span>Drivers</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="{{ Request::is('drivers') ? 'active' : '' }}"> <a href="{{url('drivers')}}"><i class="ti ti-id fs-16 me-2"></i><span>Drivers List</span></a></li>
                                    <li class="{{ Request::is('driver-history') ? 'active' : '' }}"> <a href="{{url('driver-history')}}"><i class="ti ti-history fs-16 me-2"></i><span>Driver History</span></a></li>
          
                                </ul>
                            </li>
                            @endcan
                            @can('view vehicle')
                            <li class="submenu">
                                <a href="javascript:void(0);" class="{{ Request::is('vehicles','expenses','assignments','meter-histories') ? 'active' : '' }}"><i class="ti ti-car fs-16 me-2"></i><span>Vehicles</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="{{ Request::is('vehicles') ? 'active' : '' }}"> <a href="{{url('vehicles')}}"><i class="ti ti-car fs-16 me-2"></i><span>Vehicles List</span></a></li>
                                    <li class="{{ Request::is('assignments') ? 'active' : '' }}"> <a href="{{url('assignments')}}"><i class="ti ti-user-check fs-16 me-2"></i><span>Vehicles Assignments</span></a></li>
                                    <li class="{{ Request::is('meter-histories') ? 'active' : '' }}"> <a href="{{url('meter-histories')}}"><i class="ti ti-gauge fs-16 me-2"></i><span>Meter History</span></a></li>
                                    <li class="{{ Request::is('expenses') ? 'active' : '' }}"> <a href="{{url('expenses')}}"><i class="ti ti-receipt-2 fs-16 me-2"></i><span>Expense History</span></a></li>
                                    <li class="{{ Request::is('#') ? 'active' : '' }}"> <a href="{{url('#')}}"><i class="ti ti-refresh fs-16 me-2"></i><span>Replacement Analysis</span></a></li>
                                </ul>
                            </li>
                            @endcan
                            @can('view fuel & energy')
                            <li class="submenu">
                                <a href="javascript:void(0);" class="{{ Request::is('fuel_histories','charging_histories') ? 'active' : '' }}"><i class="ti ti-gas-station fs-16 me-2"></i><span>Fuel & Energy</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="{{ Request::is('fuel_histories') ? 'active' : '' }}"> <a href="{{url('fuel_histories')}}"><i class="ti ti-gas-station fs-16 me-2"></i><span>Fuel History</span></a></li>
                                    <li class="{{ Request::is('charging_histories') ? 'active' : '' }}"> <a href="{{url('charging_histories')}}"><i class="ti ti-battery-charging fs-16 me-2"></i><span>Charging History</span></a></li>
          
                                </ul>
                            </li>
                            @endcan
                            @can('view issue')
                             <li class="submenu">
                                <a href="javascript:void(0);" class="{{ Request::is('issues','faults','recalls') ? 'active' : '' }}"><i class="ti ti-alert-triangle fs-16 me-2"></i><span>Issues</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="{{ Request::is('issues') ? 'active' : '' }}"> <a href="{{url('issues')}}"><i class="ti ti-alert-circle fs-16 me-2"></i><span>issues</span></a></li>
                                    <li class="{{ Request::is('faults') ? 'active' : '' }}"> <a href="{{url('faults')}}"><i class="ti ti-engine fs-16 me-2"></i><span>Faults</span></a></li>
                                    <li class="{{ Request::is('recalls') ? 'active' : '' }}"> <a href="{{url('recalls')}}"><i class="ti ti-rotate fs-16 me-2"></i><span>Recalls</span></a></li>
          
                                </ul>
                            </li>
                            @endcan
                            @can('view service')
                            <li class="submenu">
                                <a href="javascript:void(0);" class="{{ Request::is('services','work_orders') ? 'active' : '' }}"><i class="ti ti-tools fs-16 me-2"></i><span>Service</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="{{ Request::is('services') ? 'active' : '' }}"> <a href="{{url('services')}}"><i class="ti ti-tools fs-16 me-2"></i><span>Service History</span></a></li>
                                    <li class="{{ Request::is('work_orders') ? 'active' : '' }}"> <a href="{{url('work_orders')}}"><i class="ti ti-briefcase fs-16 me-2"></i><span>Work Orders</span></a></li>
                                    <li class="{{ Request::is('#') ? 'active' : '' }}"> <a href="{{url('#')}}"><i class="ti ti-checklist fs-16 me-2"></i><span>Service Tasks</span></a></li>
                                    <li class="{{ Request::is('#') ? 'active' : '' }}"> <a href="{{url('#')}}"><i class="ti ti-layout-dashboard fs-16 me-2"></i><span>Service Programs</span></a></li>
          
                                </ul>
                            </li>
                            @endcan
                            @can('view inspection')
                            <li class="submenu">
                                <a href="javascript:void(0);" class="{{ Request::is('inspections','failures','schedules') ? 'active' : '' }}"><i class="ti ti-clipboard-text fs-16 me-2"></i><span>Inspections</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="{{ Request::is('inspections') ? 'active' : '' }}"> <a href="{{url('inspections')}}"><i class="ti ti-history fs-16 me-2"></i><span>Inspections History</span></a></li>
                                    <li class="{{ Request::is('failures') ? 'active' : '' }}"> <a href="{{url('failures')}}"><i class="ti ti-alert-triangle fs-16 me-2"></i><span>Item Failures</span></a></li>
                                    <li class="{{ Request::is('schedules') ? 'active' : '' }}"> <a href="{{url('schedules')}}"><i class="ti ti-calendar-time fs-16 me-2"></i><span>Schedules</span></a></li>
                                </ul>
                            </li>
                            @endcan
                            @can('view report')
                            <li class="{{ Request::is('#') ? 'active' : '' }}"> <a href="{{url('#')}}"><i class="ti ti-plug-connected fs-16 me-2"></i><span>Integrations</span></a></li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="{{ Request::is('reports') ? 'active' : '' }}"><i class="ti ti-report-analytics fs-16 me-2"></i><span>Reports</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="{{ Request::is('#') ? 'active' : '' }}"> <a href="{{url('#')}}"><i class="ti ti-chart-line fs-16 me-2"></i><span>Advanced Analytics</span></a></li>
                                    <li class="{{ Request::is('reports') ? 'active' : '' }}"> <a href="{{url('reports')}}"><i class="ti ti-file-text fs-16 me-2"></i><span>Standard Reports</span></a></li>
                               </ul>
                            </li>
                            @endcan
                        </ul>
                    </li>
             
                @hasanyrole('admin|developer|manager|director|supervisor')
                @can('view user')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="{{url('users')}}"><i class="ti ti-shield-up fs-16 me-2"></i><span>Users</span></a></li>
                        <li class="{{ Request::is('roles-permissions') ? 'active' : '' }}"><a href="{{url('roles-permissions')}}"><i class="ti ti-jump-rope fs-16 me-2"></i><span>Roles & Permissions</span></a></li>
                    </ul>
                </li>
                @endcan
                @endhasanyrole
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Accounts</h6>
                    <ul>
                        <li class="{{ Request::is('profile') ? 'active' : '' }}"><a href="{{url('profile')}}"><i class="ti ti-user-circle fs-16 me-2"></i><span>Profile</span></a></li>

                        <li class="{{ Request::is('signout') ? 'active' : '' }}"><a href="{{ route('signout') }}"><i class="ti ti-logout me-2"></i><span>log out</span></a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
@endif
