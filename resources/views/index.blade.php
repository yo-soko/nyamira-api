<?php $page = 'dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast') 
@php
    $hour = date('H');
    if ($hour < 12) {
        $greeting = 'Good Morning';
    } elseif ($hour < 18) {
        $greeting = 'Good Afternoon';
    } else {
        $greeting = 'Good Evening';
    }
@endphp    
<div class="page-wrapper">
    <div class="content">
        <div class="d-lg-flex align-items-center justify-content-between mb-4">
            <div>
             
                <h2 class="mb-1">
                <img src="{{URL::asset('build/img/icons/hand01.svg')}}" class="hand-img" alt="img">
                    {{ $greeting }} <span class="text-primary fw-bold"> {{ auth()->user()->name }}</span>
                 </h2>
                <p>Its nice  to see you <span class="text-primary fw-bold">Today</span></p>
            </div>
            <ul class="table-top-head">
                <li>
                    <div class="input-icon-start position-relative">
                        <span class="input-icon-addon fs-16 text-gray-9">
                            <i class="ti ti-calendar"></i>
                        </span>
                        <input type="text" class="form-control date-range bookingrange" placeholder="Search Product">
                    </div>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse" data-bs-original-title="Collapse" class=""><i data-feather="chevron-up" class="feather-16"></i></a>
                </li>
            </ul>
        </div>
        <!-- Welcome Wrap -->
        <div class="welcome-wrap mb-4">
            <div class=" d-flex align-items-center justify-content-between flex-wrap">
                <div class="mb-3">
                    <h2 class="mb-1 text-white">Welcome Back,  {{ auth()->user()->name }}</h2>
                </div>
            
            </div>
            <div class="welcome-bg">
                <img src="{{URL::asset('build/img/bg/welcome-bg-02.svg')}}" alt="img" class="welcome-bg-01">
                <img src="{{URL::asset('build/img/bg/welcome-bg-01.svg')}}" alt="img" class="welcome-bg-03">
            </div>
        </div>	
        <!-- /Welcome Wrap -->

        <div class="row">

            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-primary-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                    
                        
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $totalStudents }}</h2>
                                <p class="fs-13">Total Learners</p>
                            </div>
                            <div class="company-bar2"></div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-info-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                    
                        
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $activeStudents }}</h2>
                                <p class="fs-13">Active Learners</p>
                            </div>
                            <div class="company-bar3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-danger-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                        
                        
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $inactiveStudents }}</h2>
                                <p class="fs-13">InActive Learners</p>
                            </div>
                            <div class="company-bar3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-teal">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $totalUsers }}</h2>
                                <p class="fs-13">Total Users</p>
                            </div>
                            <div class="company-bar3"></div>
                        </div>
                    </div>
                </div>
            </div>
          
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-info-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                    
                        
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $totalSubjects }}</h2>
                                <p class="fs-13">Total Learning Areas</p>
                            </div>
                            <div class="company-bar3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-danger-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                        
                        
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $totalLevels}}</h2>
                                <p class="fs-13">Total Grades</p>
                            </div>
                            <div class="company-bar3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-teal">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $totalStreams }}</h2>
                                <p class="fs-13">Total Streams</p>
                            </div>
                            <div class="company-bar3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-primary-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                    
                        
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $currentTerm->term_name ?? '-' }}</h2>
                                <p class="fs-13">Current Term</p>
                            </div>
                            <div class="company-bar2"></div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                function gradeLabel($score) {
                    if ($score >= 80) return 'Exceeding Expectation';
                    elseif ($score >= 60) return 'Meeting Expectation';
                    elseif ($score >= 40) return 'Approaching Expectation';
                    else return 'Below Expectation';
                }

                $gradeText = gradeLabel($topClass['average'] ?? ' ');
                $topClassGrade = gradeLabel($topClass['average'] ?? 0);
                $topStudentGrade = gradeLabel($topStudent['average'] ?? 0);
            @endphp
            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Top Stream</h6>
                    <h4>{{ $topClass['class']->level->level_name ?? '' }} - {{ $topClass['class']->stream->name ?? '' }}</h4>
                    <small class="text-success">Performance: <strong>{{ $gradeText }}</strong></small>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Exams This Term</h6>
                    <h4>{{ $examCount }}</h4>
                    <small>
                        @if($examDiff > 0)
                            <span class="text-success">📈 {{ $examDiff }} Up from last term</span>
                        @elseif($examDiff < 0)
                            <span class="text-danger">📉 {{ abs($examDiff) }} Down from last term</span>
                        @else
                            <span class="text-muted">➖ Same as last term</span>
                        @endif
                    </small>
                </div>
            </div>
            @if($topStudent)
            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Top Student</h6>
                    <h4>{{ $topStudent['student']->first_name }} {{ $topStudent['student']->last_name }}</h4>
                    <small class="text-success">Perfomance: {{$topStudentGrade }}</small>
                </div>
            </div>
            @endif

            {{-- Top Grade Level --}}
            @if($topLevel)
            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Top Grade</h6>
                    <h4>{{ $topLevel['level']->level_name }}</h4>
                    <small class="text-success">Perfomance: {{$topClassGrade }}</small>
                </div>
            </div>
            @endif
        </div>
    </div>
            
      
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0 text-gray-9">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>
@endsection