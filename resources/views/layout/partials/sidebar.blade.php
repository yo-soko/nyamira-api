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
                    <h6 class="submenu-hdr">SCHOOL MANAGEMENT</h6>
                    <ul>
                        <li class="submenu-open">
                            <ul>
                                @hasanyrole('admin|developer|manager|director|supervisor')
                                <li class="{{ Request::is('index', 'dashboard') ? 'active' : '' }}">
                                    <a href="{{ url('index') }}">
                                        <i class="fas fa-user-shield me-2"></i><span>Admin Dashboard</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('index', 'dashboard') ? 'active' : '' }}">
                                    <a href="{{url('index')}}">
                                        <i class="fas fa-users-cog fs-16 me-2"></i><span>HRM Dashboard</span>
                                    </a></li>
                                @endhasanyrole
                                @hasanyrole('developer|class_teacher')
                                <li class="{{ Request::is('tdashboard') ? 'active' : '' }}">
                                    <a href="{{ url('tdashboard') }}">
                                        <i class="fas fa-chalkboard-teacher me-2"></i><span>Class Teacher Dashboard</span>
                                    </a>
                                </li>
                                @endhasanyrole
                                @hasanyrole('developer|teacher')
                                <li class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('teacher.dashboard') }}">
                                        <i class="fas fa-user-tie"></i><span>Teacher Dashboard</span>
                                    </a>
                                </li>
                                @endhasanyrole
                                @hasanyrole('student|developer')
                                <li class="{{ Request::is('sdashboard') ? 'active' : '' }}">
                                    <a href="{{ url('sdashboard') }}">
                                        <i class="fas fa-user-graduate me-2"></i><span>Student Dashboard</span>
                                    </a>
                                </li>
                                @endhasanyrole
                            </ul>
                        </li>

                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Events</h6>
                            <ul>
                                <li class="{{ request()->is('calendar') ? 'active' : '' }}">
                                    <a href="{{ url('calendar') }}">
                                        <i class="fas fa-calendar-alt me-2"></i><span>Calendar</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @hasanyrole('admin|developer|manager|director|supervisor|class_teacher')
                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Learners Management</h6>
                            <ul>
                                <li class="{{ Request::is('students') ? 'active' : '' }}">
                                    <a href="{{ url('students') }}">
                                        <i class="fas fa-user-graduate me-2"></i><span>Learners</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('attendance', 'attendance-all') ? 'active' : '' }}">
                                    <a href="{{ url('attendance') }}">
                                        <i class="fas fa-user-check me-2"></i><span>Attendance</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endhasanyrole
                        @hasanyrole('admin|developer|manager|director|supervisor')
                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Fee Management</h6>
                            <ul>
                                <li class="{{ Request::is('fee-dashboard') ? 'active' : '' }}">
                                    <a href="{{ url('fee-dashboard') }}">
                                        <i class="ti ti-layout-dashboard fs-16 me-2"></i>
                                        <span>Fee Dashboard</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('fee-structure') ? 'active' : '' }}"><a href="{{url('fee-structure')}}"><i class="ti ti-receipt fs-16 me-2"></i><span>Fee Structure</span></a></li>
                                <li class="{{ Request::is('fee-payments') ? 'active' : '' }}"> <a href="{{url('fee-payments')}}"><i class="ti ti-receipt-2 fs-16 me-2"></i><span>Fee Payments</span></a></li>
                            </ul>
                        </li>
                        @endhasanyrole
                        @hasanyrole('student|developer')
                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Leaner's Fee Section</h6>
                            <ul>
                                <li class="{{ Request::is('fee-structure') ? 'active' : '' }}"><a href="{{url('fee-structure')}}"><i class="ti ti-receipt fs-16 me-2"></i><span>Fee Structure</span></a></li>
                                <li class="{{ request()->routeIs('student.fee-payments') ? 'active' : '' }}">
                                    <a href="{{ route('student.fee-payments') }}">
                                        <i class="ti ti-receipt-2 me-2"></i><span>Learner's Payments</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endhasanyrole

                        @hasanyrole('admin|developer|manager|director|supervisor|transport')
                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Transport</h6>
                            <ul>
                                {{-- Manage Transport --}}
                                <li class="{{ request()->routeIs('transport') ? 'active' : '' }}">
                                    <a href="{{ route('transport') }}">
                                        <i class="fas fa-bus me-2"></i>
                                        <span>Manage Transport</span>
                                    </a>
                                </li>

                                {{-- Transport Reports --}}
                                <li class="{{ request()->routeIs('transport.reports') ? 'active' : '' }}">
                                    <a href="{{ route('transport.reports') }}">
                                        <i class="fas fa-chart-line me-2"></i>
                                        <span>Transport Reports</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endhasanyrole
                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Assessments & Results</h6>
                            <ul>
                                @hasanyrole('admin|developer|manager|director|supervisor')
                                <li class="{{ Request::is('exams') ? 'active' : '' }}">
                                    <a href="{{ url('exams') }}">
                                        <i class="fas fa-file-alt me-2"></i><span>Exams</span>
                                    </a>
                                </li>
                                @endhasanyrole

                                @hasanyrole('admin|developer|manager|director|supervisor|class_teacher|teacher')
                                <li class="{{ Request::is('submit-results') ? 'active' : '' }}">
                                    <a href="{{ url('submit-results') }}">
                                        <i class="fas fa-upload me-2"></i><span>Submit Results</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('results-filter', 'results-view') ? 'active' : '' }}">
                                    <a href="{{ url('results-filter') }}">
                                        <i class="fas fa-search me-2"></i><span>View Results</span>
                                    </a>
                                </li>
                                @endhasanyrole

                                @hasanyrole('developer|student')
                                <li class="{{ Request::is('results-view','cbc-report') ? 'active' : '' }}">
                                    <a href="{{ url('cbc-report') }}">
                                        <i class="fas fa-search me-2"></i><span>View Results</span>
                                    </a>
                                </li>
                                @endhasanyrole
                            </ul>
                        </li>

                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Library</h6>
                            <ul>

                                <li><a href="{{ route('library.index') }}"><i class="ti ti-book me-2"></i> <span>Library</span></a></li>
                                @hasanyrole('admin|developer|manager|director|supervisor|class_teacher|teacher')
                                <li><a href="{{ route('library-categories.index') }}"><i class="ti ti-tag"></i> <span>Book Categories</span></a></li>
                                @endhasanyrole
                            </ul>
                        </li>

                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Classroom Tools</h6>
                            <ul>
                                <li class="{{ Request::is('designation') ? 'active' : '' }}">
                                    <a href="#"><i class="fas fa-tasks me-2"></i><span>Assessment Book</span></a>
                                </li>
                                <li class="{{ Request::is('diary') ? 'active' : '' }}">
                                    <a href="#"><i class="fas fa-book me-2"></i><span>Diary</span></a>
                                </li>
                            </ul>
                        </li>

                        @hasanyrole('admin|developer|manager|director|supervisor')
                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Academic Setup</h6>
                            <ul>
                                <li class="{{ Request::is('subjects') ? 'active' : '' }}"><a href="{{ url('subjects') }}"><i class="fas fa-book-open me-2"></i> Learning Areas</a></li>
                                <li class="{{ Request::is('class-levels') ? 'active' : '' }}"><a href="{{ url('class-levels') }}"><i class="fas fa-layer-group me-2"></i> Grades Setup</a></li>
                                <li class="{{ Request::is('streams') ? 'active' : '' }}"><a href="{{ url('streams') }}"><i class="fas fa-stream me-2"></i> Streams Setup</a></li>
                                <li class="{{ Request::is('terms') ? 'active' : '' }}"><a href="{{ url('terms') }}"><i class="fas fa-calendar-alt me-2"></i><span>Terms</span></a></li>
                                <li class="{{ Request::is('school-classes') ? 'active' : '' }}"><a href="{{ url('school-classes') }}"><i class="fas fa-chalkboard me-2"></i><span>Classrooms</span></a></li>
                                <li class="{{ Request::is('department-grid') ? 'active' : '' }}"><a href="{{ url('department-grid') }}"><i class="fas fa-compass me-2"></i><span>Departments</span></a></li>
                                <li class="{{ Request::is('guardians') ? 'active' : '' }}"><a href="{{ url('guardians') }}"><i class="fas fa-user-shield me-2"></i><span>Parent/Guardian</span></a></li>
                                <li class="{{ Request::is('teachers') ? 'active' : '' }}"><a href="{{ url('teachers') }}"><i class="fas fa-chalkboard-teacher me-2"></i><span>Teachers</span></a></li>
                            </ul>
                        </li>
                        @endhasanyrole

                    </ul>
                </li>


                <li class="submenu-open">
                    <h6 class="submenu-hdr">HRM</h6>
                    <ul>
                            <li class="{{ Request::is('employees-list','add-employee','edit-employee','employee-details') ? 'active' : '' }}"><a href="{{url('employees-list')}}"><i class="ti ti-user fs-16 me-2"></i><span>Employees</span></a></li>
                            <li class="{{ Request::is('department-grid') ? 'active' : '' }}"><a href="{{url('department-grid')}}"><i class="ti ti-compass fs-16 me-2"></i><span>Departments</span></a></li>
                            <li class="{{ Request::is('designation') ? 'active' : '' }}"><a href="{{url('designation')}}"><i class="ti ti-git-merge fs-16 me-2"></i><span>Designation</span></a></li>
                            <li class="{{ Request::is('shift') ? 'active' : '' }}"><a href="{{url('shift')}}"><i class="ti ti-arrows-shuffle fs-16 me-2"></i><span>Shifts</span></a></li>
                            @hasanyrole('admin|developer|manager|director|supervisor|class_teacher|teacher')
                            <li class="{{ Request::is('attendance-employee','attendance-admin') ? 'active' : '' }}"> <a href="{{url('attendance-admin')}}"><i class="ti ti-user-cog fs-16 me-2"></i><span>Attendence</span></a></li>
                            @endhasanyrole
                            <li class="submenu">
                                    <a href="javascript:void(0);" class="{{ Request::is('leaves-admin','leaves','leave-types') ? 'active' : '' }}"><i class="ti ti-calendar fs-16 me-2"></i><span>Leaves</span><span class="menu-arrow"></span></a>
                                    <ul>
                                            @hasanyrole('admin|developer|manager|director|supervisor')
                                            <li><a href="{{url('leaves-admin')}}" class="{{ Request::is('leaves-admin') ? 'active' : '' }}">Leave Applications</a></li>
                                            @endhasanyrole

                                            @hasanyrole('admin|developer|manager|director|supervisor|class_teacher|teacher')
                                            <li><a href="{{url('leaves')}}" class="{{ Request::is('leaves') ? 'active' : '' }}">My Leaves</a></li>

                                            <li><a href="{{url('leave-types')}}" class="{{ Request::is('leave-types') ? 'active' : '' }}">Leave Types</a></li>
                                            @endhasanyrole
                                    </ul>
                            </li>
                            <li class="{{ Request::is('holidays') ? 'active' : '' }}"><a href="{{url('holidays')}}" ><i class="ti ti-calendar-share fs-16 me-2"></i><span>Holidays</span></a>
                            </li>
                            @hasanyrole('admin|developer|manager|director|supervisor|class_teacher|teacher')
                            <li class="submenu">
                                    <a href="{{url('employee-salary')}}" class="{{ Request::is('employee-salary','payslip') ? 'active' : '' }}"><i class="ti ti-file-dollar fs-16 me-2"></i><span>Payroll</span><span class="menu-arrow"></span></a>
                                    <ul>
                                            <li><a href="{{url('employee-salary')}}" class="{{ Request::is('employee-salary') ? 'active' : '' }}">Employee Salary</a></li>
                                    </ul>
                            </li>
                            @endhasanyrole
                    </ul>
                </li>


                @hasanyrole('admin|developer|manager|director|supervisor')
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="{{url('users')}}"><i class="ti ti-shield-up fs-16 me-2"></i><span>Users</span></a></li>
                        <li class="{{ Request::is('roles-permissions') ? 'active' : '' }}"><a href="{{url('roles-permissions')}}"><i class="ti ti-jump-rope fs-16 me-2"></i><span>Roles & Permissions</span></a></li>
                        <li class="{{ Request::is('delete-account') ? 'active' : '' }}"><a href="{{url('delete-account')}}"><i class="ti ti-trash-x fs-16 me-2"></i><span>Delete Account Request</span></a></li>
                    </ul>
                </li>
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
