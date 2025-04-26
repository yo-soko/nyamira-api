	
@if(!Route::is(['pos','pos-2','pos-3','pos-4','pos-5']))
<div class="header">
    <div class="main-header">
        <!-- Logo -->
        <div class="header-left active">
            <a href="{{url('index')}}" class="logo logo-normal">
                <img src="{{URL::asset('build/img/logo.svg')}}" alt="Img">
            </a>
            <a href="{{url('index')}}" class="logo logo-white">
                <img src="{{URL::asset('build/img/logo-white.svg')}}" alt="Img">
            </a>
            <a href="{{url('index')}}" class="logo-small">
                <img src="{{URL::asset('build/img/logo-small.png')}}" alt="Img">
            </a>
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
					<li class="nav-item nav-searchinputs">
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
					</li>
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
                        <a href="{{url('activities')}}" class="btn btn-primary btn-md w-100">View all</a>
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
                            <img src="{{ asset('storage/' . session('user_image', 'default.png')) }}" alt="Img" class="img-fluid">
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profileset d-flex align-items-center">
                        <span class="user-img me-2">
                            <img src="{{ asset('storage/' . session('user_image', 'default.png')) }}" alt="Img">
                        </span>
                        <div>
                            <h6 class="fw-medium">{{ session('user_name') }}</h6>
                        </div>
                    </div>
                    <a class="dropdown-item" href="{{url('profile')}}"><i class="ti ti-user-circle me-2"></i>MyProfile</a>
                    <a class="dropdown-item" href="#"><i class="ti ti-file-text me-2"></i>Reports</a>
                    <a class="dropdown-item" href="{{url('general-settings')}}"><i class="ti ti-settings-2 me-2"></i>Settings</a>
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
                <a class="dropdown-item" href="{{url('general-settings')}}">Settings</a>
                <a class="dropdown-item" href="{{ route('signout') }}">Logout</a>
            </div>
        </div>
        <!-- /Mobile Menu -->
    </div>
</div>
@endif

    	
    @if(Route::is(['pos','pos-2','pos-3','pos-4','pos-5']))
  
<!-- Header -->
<div class="header pos-header">
			
    <!-- Logo -->
     <div class="header-left active">
        <a href="{{url('index')}}" class="logo logo-normal">
            <img src="{{URL::asset('build/img/logo.svg')}}"  alt="Img">
        </a>
        <a href="{{url('index')}}" class="logo logo-white">
            <img src="{{URL::asset('build/img/logo-white.svg')}}"  alt="Img">
        </a>
        <a href="{{url('index')}}" class="logo-small">
            <img src="{{URL::asset('build/img/logo-small.png')}}"  alt="Img">
        </a>
    </div>
    <!-- /Logo -->
    
    <a id="mobile_btn" class="mobile_btn d-none" href="#sidebar">
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
            <span class="bg-teal text-white d-inline-flex align-items-center"><img src="{{URL::asset('build/img/icons/clock-icon.svg')}}" alt="img" class="me-2">09:25:32</span>
        </li>
        <!-- /Search -->
        
        <li class="nav-item pos-nav">
            <a href="{{url('index')}}" class="btn btn-purple btn-md d-inline-flex align-items-center">
                <i class="ti ti-world me-1"></i>Dashboard
            </a>
        </li>

        <!-- Select Store -->
        <li class="nav-item dropdown has-arrow main-drop select-store-dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link select-store"
                data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="{{URL::asset('build/img/store/store-01.png')}}" alt="Store Logo" class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name">Freshmart</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="{{URL::asset('build/img/store/store-01.png')}}" alt="Store Logo" class="img-fluid">Freshmart
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="{{URL::asset('build/img/store/store-02.png')}}" alt="Store Logo" class="img-fluid">Grocery Apex
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="{{URL::asset('build/img/store/store-03.png')}}" alt="Store Logo" class="img-fluid">Grocery Bevy
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="{{URL::asset('build/img/store/store-04.png')}}" alt="Store Logo" class="img-fluid">Grocery Eden
                </a>
            </div>
        </li>
        <!-- /Select Store -->
        
        <li class="nav-item nav-item-box">
            <a href="#" data-bs-toggle="modal" data-bs-target="#calculator" class="bg-orange border-orange text-white"><i class="ti ti-calculator"></i></a>
        </li>
        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Maximize" >
                <i class="ti ti-maximize"></i>
            </a>
        </li>
        <li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cash Register">
            <a href="#" data-bs-toggle="modal" data-bs-target="#cash-register"><i class="ti ti-cash"></i></a>
        </li>
        <li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print Last Reciept">
            <a href="#"><i class="ti ti-printer"></i></a>
        </li>
        <li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Today’s Sale">
            <a href="#" data-bs-toggle="modal" data-bs-target="#today-sale"><i class="ti ti-progress"></i></a>
        </li>
        <li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Today’s Profit">
            <a href="#" data-bs-toggle="modal" data-bs-target="#today-profit"><i class="ti ti-chart-infographic"></i></a>
        </li>
        <li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="POS Settings">
            <a href="{{url('pos-settings')}}"><i class="ti ti-settings"></i></a>
        </li>
        <li class="nav-item dropdown has-arrow main-drop profile-nav">
            <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info p-0">
                    <span class="user-letter">
                        <img src="{{URL::asset('build/img/profiles/avator1.jpg')}}" alt="Img" class="img-fluid">
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img"><img src="{{URL::asset('build/img/profiles/avator1.jpg')}}" alt="Img">
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>John Smilga</h6>
                            <h5>Super Admin</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="#"><i class="me-2" data-feather="user"></i>My
                        Profile</a>
                    <a class="dropdown-item" href="#"><i class="me-2" data-feather="settings"></i>Settings</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{ route('signout') }}"><img src="{{URL::asset('build/img/icons/log-out.svg')}}" class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->
    
    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#">My Profile</a>
            <a class="dropdown-item" href="#">Settings</a>
            <a class="dropdown-item" href="{{ route('signout') }}">Logout</a>
        </div>
    </div>
    <!-- /Mobile Menu -->
</div>
<!-- Header -->
    @endif