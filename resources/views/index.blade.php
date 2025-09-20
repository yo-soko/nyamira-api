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
            <!-- <ul class="table-top-head">
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
            </ul> -->
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

       
    
          
        </div>
    </div>
            
      
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0 text-gray-9">&copy;  All Right Reserved</p>
        <!-- <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary"></a></p> -->
    </div>
</div>
@endsection