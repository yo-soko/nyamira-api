@if (! Route::is(['pos','pos-2','pos-3','pos-4','pos-5']))
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
                                <h6 class="fs-12 fw-normal mb-1">Adrian Herman</h6>
                                <p class="fs-10 mb-0">System Admin</p>
                        </div>
                        <div class="sidebar-nav mb-3">
                                <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified bg-transparent" role="tablist">
                                        <li class="nav-item"><a class="nav-link active border-0" href="#">Menu</a></li>
                                        <li class="nav-item"><a class="nav-link border-0" href="{{url('chat')}}">Chats</a></li>
                                        <li class="nav-item"><a class="nav-link border-0" href="{{url('email')}}">Inbox</a></li>
                                </ul>
                        </div>
                </div>
                <div class="sidebar-header p-3 pb-0 pt-2">
                        <div class="text-center rounded bg-light p-2 mb-4 sidebar-profile d-flex align-items-center">
                                <div class="avatar avatar-md onlin">
                                        <img src="{{URL::asset('build/img/customer/customer15.jpg')}}" alt="Img" class="img-fluid rounded-circle">
                                </div>
                                <div class="text-start sidebar-profile-info ms-2">
                                        <h6 class="fs-12 fw-normal mb-1">Adrian Herman</h6>
                                        <p class="fs-10">System Admin</p>
                                </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between menu-item mb-3">
                                <div>
                                        <a href="{{url('index')}}" class="btn btn-sm btn-icon bg-light">
                                                <i class="ti ti-layout-grid-remove"></i>
                                        </a>
                                </div>
                                <div>
                                        <a href="{{url('chat')}}" class="btn btn-sm btn-icon bg-light">
                                                <i class="ti ti-brand-hipchat"></i>
                                        </a>
                                </div>
                                <div>
                                        <a href="{{url('email')}}" class="btn btn-sm btn-icon bg-light position-relative">
                                                <i class="ti ti-message"></i>
                                        </a>
                                </div>
                                <div class="notification-item">
                                        <a href="{{url('activities')}}" class="btn btn-sm btn-icon bg-light position-relative">
                                                <i class="ti ti-bell"></i>
                                                <span class="notification-status-dot"></span>
                                        </a>
                                </div>
                                <div class="me-0">
                                        <a href="{{url('general-settings')}}" class="btn btn-sm btn-icon bg-light">
                                                <i class="ti ti-settings"></i>
                                        </a>
                                </div>
                        </div>
                </div>
                <div class="sidebar-inner slimscroll">
                        <div id="sidebar-menu" class="sidebar-menu">
                                <ul>
                                     
                                        <li class="submenu-open">
                                                <h6 class="submenu-hdr">HRM</h6>
                                                <ul>
                                                        <li class="{{ Request::is('index', 'dashboard') ? 'active' : '' }}"><a href="{{url('index')}}"><i class="ti ti-dashboard fs-16 me-2"></i><span>Dashboard</span></a></li>
                                                        <li class="{{ Request::is('employees-list','add-employee','edit-employee','employee-details') ? 'active' : '' }}"><a href="{{url('employees-list')}}"><i class="ti ti-user fs-16 me-2"></i><span>Employees</span></a></li>
                                                        <li class="{{ Request::is('department-grid') ? 'active' : '' }}"><a href="{{url('department-grid')}}"><i class="ti ti-compass fs-16 me-2"></i><span>Departments</span></a></li>
                                                        <li class="{{ Request::is('designation') ? 'active' : '' }}"><a href="{{url('designation')}}"><i class="ti ti-git-merge fs-16 me-2"></i><span>Designation</span></a></li>
                                                        <li class="{{ Request::is('shift') ? 'active' : '' }}"><a href="{{url('shift')}}"><i class="ti ti-arrows-shuffle fs-16 me-2"></i><span>Shifts</span></a></li>
                                                        <li class="{{ Request::is('attendance-employee','attendance-admin') ? 'active' : '' }}"> <a href="{{url('attendance-admin')}}"><i class="ti ti-user-cog fs-16 me-2"></i><span>Attendence</span></a></li>
                                                        <li class="submenu">
                                                                <a href="javascript:void(0);" class="{{ Request::is('leaves-admin','leaves-employee','leave-types') ? 'active' : '' }}"><i class="ti ti-calendar fs-16 me-2"></i><span>Leaves</span><span class="menu-arrow"></span></a>
                                                                <ul>
                                                                        <li><a href="{{url('leaves-admin')}}" class="{{ Request::is('leaves-admin') ? 'active' : '' }}">Admin Leaves</a></li>
                                                                        <li><a href="{{url('leaves-employee')}}" class="{{ Request::is('leaves-employee') ? 'active' : '' }}">Employee Leaves</a></li>
                                                                        <li><a href="{{url('leave-types')}}" class="{{ Request::is('leave-types') ? 'active' : '' }}">Leave Types</a></li>
                                                                </ul>
                                                        </li>
                                                        <li class="{{ Request::is('holidays') ? 'active' : '' }}"><a href="{{url('holidays')}}" ><i class="ti ti-calendar-share fs-16 me-2"></i><span>Holidays</span></a>
                                                        </li>
                                                        @useronly
                                                        <li class="submenu">
                                                                <a href="{{url('employee-salary')}}" class="{{ Request::is('employee-salary','payslip') ? 'active' : '' }}"><i class="ti ti-file-dollar fs-16 me-2"></i><span>Payroll</span><span class="menu-arrow"></span></a>
                                                                <ul>
                                                                        <li><a href="{{url('employee-salary')}}" class="{{ Request::is('employee-salary') ? 'active' : '' }}">Employee Salary</a></li>
                                                                </ul>
                                                        </li>
                                                        @enduseronly
                                                </ul>
                                        </li>
                                        @useronly
                                        <li class="submenu-open">
                                                <h6 class="submenu-hdr">User Management</h6>
                                                <ul>
                                                        <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="{{url('users')}}" ><i class="ti ti-shield-up fs-16 me-2"></i><span>Users</span></a></li>
                                                        <li class="{{ Request::is('roles-permissions') ? 'active' : '' }}"><a href="{{url('roles-permissions')}}"><i class="ti ti-jump-rope fs-16 me-2"></i><span>Roles & Permissions</span></a></li>
                                                        <li class="{{ Request::is('delete-account') ? 'active' : '' }}"><a href="{{url('delete-account')}}"><i class="ti ti-trash-x fs-16 me-2"></i><span>Delete Account Request</span></a></li>
                                                </ul>
                                        </li>
                                        @enduseronly
                                        <li class="submenu-open">
                                                <h6 class="submenu-hdr">Accounts</h6>
                                                <ul>
                                                        <li class="{{ Request::is('profile') ? 'active' : '' }}"><a href="{{url('profile')}}"><i class="ti ti-user-circle fs-16 me-2"></i><span>Profile</span></a></li>
                                                </ul>
                                                <ul>
                                                        <li class="{{ Request::is('signout') ? 'active' : '' }}"><a href="{{ route('signout') }}"><i class="ti ti-logout me-2"></i><span>log out</span></a></li>
                                                </ul>
                                        </li>  
                                         
                                </ul>
                        </div>
                </div>
        </div>
@endif
