	
@if(!Route::is(['pos','pos-2','pos-3','pos-4','pos-5']))
<div class="header">
    <div class="main-header">
        <!-- Logo -->
        <div class="header-left active">
           
        </div>
        <!-- /Logo -->
        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <!-- Header Menu -->
        <ul class="nav user-menu">


            <!-- Search -->
            <li class="nav-item time-nav">
                <span id="realtime-clock" style="font-weight: bold;" class="btn bg-purple"></span>
            </li>

            @push('scripts')
            <script>
                function updateClock() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    const timeString = `${hours}:${minutes}:${seconds}`;
                    document.getElementById('realtime-clock').textContent = timeString;
                }

                setInterval(updateClock, 1000); // Update every second
                updateClock(); // Initial call
            </script>
            @endpush

            <li class="nav-item pos-nav">
                <a href="{{ url('signout') }}" class="btn btn-purple btn-md d-inline-flex align-items-center gap-2">
                    <i class="ti ti-logout"></i> Logout
                </a>
            </li>
            <!-- /Search -->
            <li class="nav-item pos-nav">
                <form action="{{ route('attendance-employee') }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id ?? '' }}">
                    <button type="submit" class="btn bg-success btn-lg d-inline-flex align-items-center">
                        <img src="{{ URL::asset('build/img/icons/clock-icon.svg') }}" alt="img" class="me-2">
                        Clock-in/Clock-out
                    </button>
                </form>
            </li>

           <!-- Search -->
					<!-- <li class="nav-item nav-searchinputs">
						<div class="top-nav-search">
							<a href="javascript:void(0);" class="responsive-search">
								<i class="fa fa-search"></i>
							</a>
							<form action="#" class="dropdown">
								<div class="searchinputs input-group dropdown-toggle" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="outside">
									<input type="text" placeholder="Search">
									<div class="search-addon">
										<span><i class="ti ti-search"></i></span>
									</div>
									<span class="input-group-text">
										<kbd class="d-flex align-items-center"><img src="{{URL::asset('build/img/icons/command.svg')}}" alt="img" class="me-1">K</kbd>
									</span>
								</div>
								
							</form>
						</div>
					</li> -->
					<!-- /Search -->

            

            <!-- Flag -->
            <li class="nav-item dropdown has-arrow flag-nav nav-item-box">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);"
                    role="button">
                    <img src="{{URL::asset('build/img/flags/ke.png')}}" alt="Language" class="img-fluid">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                   
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{URL::asset('build/img/flags/ke.png')}}" alt="Img" height="16">Kenya
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="{{URL::asset('build/img/flags/english.svg')}}" alt="Img" height="16">English
                    </a>
                </div>
            </li>
            <!-- /Flag -->

            <li class="nav-item nav-item-box">
                <a href="javascript:void(0);" id="btnFullscreen">
                    <i class="ti ti-maximize"></i>
                </a>
            </li>
            <li class="nav-item nav-item-box">
                <a href="#">
                    <i class="ti ti-mail"></i>
                    <span class="badge rounded-pill">1</span>
                </a>
            </li>
            <!-- Notifications -->
            <li class="nav-item dropdown nav-item-box">
                <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <i class="ti ti-bell"></i>
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <h5 class="notification-title">Notifications</h5>
                        <a href="javascript:void(0)" class="clear-noti">Mark all as read</a>
                    </div>
                    
                    <div class="topnav-dropdown-footer d-flex align-items-center gap-3">
                        <a href="#" class="btn btn-secondary btn-md w-100">Cancel</a>
                        <a href="#" class="btn btn-primary btn-md w-100">View all</a>
                    </div>
                </div>
            </li>
            <!-- /Notifications -->

            <li class="nav-item nav-item-box">
                <a href="{{url('general-settings')}}"><i class="ti ti-settings"></i></a>
            </li>
            <li class="nav-item dropdown has-arrow main-drop profile-nav">
                <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                    <span class="user-info p-0">
                        <span class="user-letter">
                           <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('build/img/users/default.png') }}" alt="Img">
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profileset d-flex align-items-center">
                        <span class="user-img me-2">
                           <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('build/img/users/default.png') }}" alt="Img">
                        </span>
                        <div>
                            <h6 class="fw-medium">{{ auth()->user()->name }}</h6>
                        </div>
                    </div>
                    <a class="dropdown-item" href="{{url('profile')}}"><i class="ti ti-user-circle me-2"></i>MyProfile</a>
                    <a class="dropdown-item" href="#"><i class="ti ti-file-text me-2"></i>Reports</a>
                    <a class="dropdown-item" href="#"><i class="ti ti-settings-2 me-2"></i>Settings</a>
                    <hr class="my-2">
                    <a class="dropdown-item logout pb-0" href="{{ route('signout') }}"><i class="ti ti-logout me-2"></i>Logout</a>
                </div>
            </li>
        </ul>
        <!-- /Header Menu -->

        <!-- Mobile Menu -->
        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{url('profile')}}">My Profile</a>
                <a class="dropdown-item" href="#">Settings</a>
                <a class="dropdown-item" href="{{ route('signout') }}">Logout</a>
            </div>
        </div>
        <!-- /Mobile Menu -->
    </div>
</div>
@endif
